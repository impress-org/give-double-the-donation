<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use Give\Donations\Models\Donation;
use Give\Helpers\Form\Utils;
use Give\Log\Log;
use GiveDoubleTheDonation\DoubleTheDonation\Payment;

/**
 * @since 2.1.2
 */
class RegisterDonationOnDTD {
    /**
     * Called on give_insert_payment action.
     *
     * @since 2.1.2
     */
    public function __invoke($payment_id, $payment_data) {
        // V3 forms are handled by the field scope
        if (!isset($_POST) || Utils::isV3Form((int)$payment_data['give_form_id'])) {
            Log::info(
                'Double the Donation: Skipping donation registration for V3 form',
                [
                    'category' => 'Payment',
                    'source' => 'Double the Donation add-on',
                    'payment_id' => $payment_id,
                ]
            );

            return;
        }

        give(Payment::class)->addPaymentMeta($payment_id, $payment_data);

        $donation = Donation::find((int)$payment_id);

        // handle for single donations only
        if ($donation->type->isSingle()) {
            give(Payment::class)->addDonationToDTD($payment_id, $payment_data);
        }
    }
}
