<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\FieldScope;

use Closure;
use Give\Donations\Models\Donation;
use Give\Donations\Models\DonationNote;
use Give\Log\Log;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;

/**
 * Save payment meta and send data to DTD 360
 *
 * @unreleased send data to DTD 360match pro
 * @since      2.0.0
 */
class HandleData
{
    /**
     * @since 2.0.0
     */
    public function __invoke(): Closure
    {
        // Scope callback
        return function (DoubleTheDonationField $field, $company, Donation $donation) {
            if ($this->isRequiredDataSet($company)) {
                $this->save($company, $donation);
                $this->send($company, $donation);
            }
        };
    }

    /**
     * Check if company required data is set
     *
     * @since 2.0.0
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
     * @since 2.0.0
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

    /**
     * Send data to DTD 360match pro
     *
     * @unreleased
     */
    private function send(array $companyData, Donation $donation): void
    {
        if ( ! $dtdPublicKey = give_get_option('public_dtd_key')) {
            return;
        }

        $data = [
            '360matchpro_public_key' => $dtdPublicKey,
            'donor_first_name' => $donation->firstName,
            'donor_last_name' => $donation->lastName,
            'donor_email' => $donation->email,
            'campaign' => $donation->formId,
            'donation_amount' => $donation->amount->formatToDecimal(),
            'donation_identifier' => $donation->getSequentialId(),
            'partner_identifier' => 'GiveWP',
            'doublethedonation_company_id' => $companyData['company_id'],
            'doublethedonation_entered_text' => $companyData['entered_text'],
        ];

        $response = wp_remote_post('https://doublethedonation.com/api/360matchpro/v1/register_donation',
            [
                'method' => 'POST',
                'blocking' => true,
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'body' => json_encode($data),
                'cookies' => [],
            ]
        );

        $responseBody = json_decode(wp_remote_retrieve_body($response));

        // API fail check.
        if (201 !== wp_remote_retrieve_response_code($response)) {
            Log::error(
                'Double the Donation',
                $responseBody
            );

            return;
        }

        DonationNote::create([
            'donationId' => $donation->id,
            'content' => esc_html__('Donation information added to Double the Donation 360MatchPro', 'give-double-the-donation')
        ]);
    }
}
