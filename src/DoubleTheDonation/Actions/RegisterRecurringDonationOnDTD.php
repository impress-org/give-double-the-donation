<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use GiveDoubleTheDonation\DoubleTheDonation\Payment;
use Give_Payment;

class RegisterRecurringDonationOnDTD {
    public function __invoke(Give_Payment $payment) {
        give(Payment::class)->addDonationToDTD($payment->ID, $payment);
    }
}
