<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;

/**
 * Action used to check if Company matching field should be displayed on confirmation page
 *
 * @since 2.0.0
 */
class DisplayFieldLabel
{
    /**
     * @since 2.0.0
     */
    public function __invoke(string $label, DoubleTheDonationField $field, Donation $donation): ?string
    {
        if (give_get_meta($donation->id, 'doublethedonation_company_id', true)) {
            return $label;
        }

        return null;
    }
}
