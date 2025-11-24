<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use Give\Donations\Models\Donation;
use Give\Helpers\Form\Utils;
use Give\Log\Log;
use GiveDoubleTheDonation\DoubleTheDonation\Payment;

class RegisterDonationOnDTD {
    public function __invoke($payment_id, $payment_data) {
        // V3 forms are handled by the field scope
        if (!isset($_POST) || Utils::isV3Form((int)$payment_data['give_form_id'])) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                Log::warning(
                    'Double the Donation: Skipping donation registration for V3 form',
                    [
                        'category' => 'Payment',
                        'source' => 'Double the Donation add-on',
                        'payment_id' => $payment_id,
                    ]
                );
            }

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
