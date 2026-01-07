<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use GiveDoubleTheDonation\DoubleTheDonation\Payment;
use Give_Payment;

/**
 * @since 2.1.2
 */
class RegisterRecurringDonationOnDTD {
    /**
     * Called on give_recurring_record_payment action.
     *
     * @since 2.1.2
     */
    public function __invoke(Give_Payment $payment) {
        give(Payment::class)->addDonationToDTD($payment->ID, $payment);
    }
}
