<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use GiveDoubleTheDonation\DoubleTheDonation\Payment;
use Give_Payment;

/**
 * @unreleased
 */
class RegisterRecurringDonationOnDTD {
    /**
     * Called on give_recurring_record_payment action.
     *
     * @unreleased
     */
    public function __invoke(Give_Payment $payment) {
        give(Payment::class)->addDonationToDTD($payment->ID, $payment);
    }
}
