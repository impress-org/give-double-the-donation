<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\Addon\View;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;

/**
 * Action used to displayed DTD Company Matching value/input on confirmation page
 *
 * @since 2.1.0 renamed to DisplayField
 * @since 2.0.0
 */
class DisplayField
{
    /**
     * @since 2.1.0
     */
    public function value(?string $value, DoubleTheDonationField $field, Donation $donation): ?string
    {
        return View::load('dtd-receipt', [
            'donation' => $donation,
            'receiptId' => $_GET['receipt-id'] ?? null
        ]);
    }
}
