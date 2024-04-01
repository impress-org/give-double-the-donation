<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\FieldScope;

use Closure;
use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;

/**
 * Save payment meta - field scope action
 *
 * @unreleased
 */
class PaymentMeta
{
    /**
     * @unreleased
     */
    public function __invoke(): Closure
    {
        // Scope callback
        return function (DoubleTheDonationField $field, $company, Donation $donation) {
            if ($this->isRequiredDataSet($company)) {
                $this->save($company, $donation);
            }
        };
    }

    /**
     * Check if company required data is set
     *
     * @unreleased
     */
    private function isRequiredDataSet($data): bool
    {
        if ( ! is_array($data)) {
            return false;
        }

        foreach (['company_id', 'company_name', 'entered_text'] as $name) {
            if ( ! array_key_exists($name, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Save payment meta
     *
     * @unreleased
     */
    private function save(array $companyData, Donation $donation)
    {
        foreach ($companyData as $name => $value) {
            give_update_meta(
                $donation->id,
                'doublethedonation_' . $name,
                $value
            );
        }

        give_update_meta(
            $donation->id,
            '_give_donation_company',
            $companyData['company_name']
        );

        give()->donor_meta->update_meta(
            $donation->donorId,
            '_give_donor_company',
            $companyData['company_name']
        );
    }
}
