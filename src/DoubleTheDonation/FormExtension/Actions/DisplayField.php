<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\Addon\View;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;

/**
 * Action used to displayed DTD Company Matching value/input on confirmation page
 *
 * @unreleased renamed to DisplayField
 * @since 2.0.0
 */
class DisplayField
{
    /**
     * @unreleased
     */
    public function value(?string $value, DoubleTheDonationField $field, Donation $donation): ?string
    {
        return View::load('dtd-receipt', [
            'donation' => $donation,
        ]);
    }
}
