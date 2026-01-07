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
 * @since 2.0.2 send data to DTD 360match pro
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
            if (defined('WP_DEBUG') && WP_DEBUG) {
                Log::info(
                    'Double the Donation: Processing company matching data for donation',
                    [
                        'category' => 'Payment',
                        'source' => 'Double the Donation add-on',
                        'donation_id' => $donation->id,
                        'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
                        'company_data' => is_array($company) ? [
                            'company_id' => $company['company_id'] ?? null,
                            'company_name' => $company['company_name'] ?? null,
                        ] : null,
                    ]
                );
            }

            $this->save((array)$company, $donation);
            $this->send((array)$company, $donation);
        };
    }

    /**
     * Save payment meta
     *
     * @since 2.1.0 update visibility
     * @since 2.0.0
     */
    public function save(array $companyData, Donation $donation)
    {
        foreach ((array)$companyData as $name => $value) {
            give_update_meta(
                $donation->id,
                'doublethedonation_' . $name,
                $value
            );
        }

        if (isset($companyData['company_name'])) {
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

    /**
     * Send data to DTD 360match pro
     *
     * @unreleased add logging
     * @since 2.1.0 update visibility
     * @since 2.0.2
     */
    public function send(array $companyData = [], Donation $donation): void
    {
        if ( ! $dtdPublicKey = give_get_option('public_dtd_key')) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                Log::warning(
                    'Double the Donation: Public key not configured. Skipping API call.',
                    [
                        'category' => 'Payment',
                        'source' => 'Double the Donation add-on',
                        'donation_id' => $donation->id,
                    ]
                );
            }

            return;
        }

        $data = [
            '360matchpro_public_key' => $dtdPublicKey,
            'donor_first_name' => $donation->firstName,
            'donor_last_name' => $donation->lastName,
            'donor_email' => $donation->email,
            'campaign' => $donation->formId,
            'donation_amount' => $donation->amount->formatToDecimal(),
            'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
            'recurring' => $donation->type->isRecurring(),
            'partner_identifier' => 'GiveWP',
        ];

        if (isset($companyData['company_id'])) {
            $data['doublethedonation_company_id'] = $companyData['company_id'];
        }

        if (isset($companyData['entered_text'])) {
            $data['doublethedonation_entered_text'] = $companyData['entered_text'];
        }

        Log::info(
            'Double the Donation: Sending donation data to 360MatchPro API',
            [
                'category' => 'Payment',
                'source' => 'Double the Donation add-on',
                'donation_id' => $donation->id,
                'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
                'company_id' => $companyData['company_id'],
                'company_name' => $companyData['company_name'],
                'data' => $data,
            ]
        );

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

        $responseCode = wp_remote_retrieve_response_code($response);
        $responseMessage = wp_remote_retrieve_response_message($response);
        $responseBody = json_decode(wp_remote_retrieve_body($response), true);
        $responseHeaders = wp_remote_retrieve_headers($response);
        $responseHeadersArray = (is_wp_error($response) || ! $responseHeaders || ! method_exists($responseHeaders, 'getAll')) ? [] : $responseHeaders->getAll();

        // Log the raw response from the server
        Log::http(
            'Double the Donation: Received response from 360MatchPro API',
            [
                'category' => 'Payment',
                'source' => 'Double the Donation add-on',
                'donation_id' => $donation->id,
                'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
                'response_headers' => $responseHeadersArray,
                'response_body' => $responseBody,
                'raw_response' => is_wp_error($response) ? [
                    'error_code' => $response->get_error_code(),
                    'error_message' => $response->get_error_message(),
                    'error_data' => $response->get_error_data(),
                ] : $response,
            ]
        );

        // API fail check.
        if (201 !== $responseCode) {
            Log::error(
                'Double the Donation: Failed to register donation with 360MatchPro API',
                [
                    'category' => 'Payment',
                    'source' => 'Double the Donation add-on',
                    'donation_id' => $donation->id,
                    'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
                    'response_code' => $responseCode,
                    'response_message' => $responseMessage,
                    'response_headers' => $responseHeadersArray,
                    'response_body' => $responseBody,
                    'raw_response' => is_wp_error($response) ? [
                        'error_code' => $response->get_error_code(),
                        'error_message' => $response->get_error_message(),
                        'error_data' => $response->get_error_data(),
                    ] : $response,
                    'company_id' => $companyData['company_id'],
                ]
            );

            return;
        }

        Log::success(
            'Double the Donation: Successfully registered donation with 360MatchPro API',
            [
                'category' => 'Payment',
                'source' => 'Double the Donation add-on',
                'donation_id' => $donation->id,
                'donation_identifier' => $donation->getSequentialId() ?? $donation->id,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
                'response_headers' => $responseHeadersArray,
                'response_body' => $responseBody,
                'company_id' => $companyData['company_id'],
                'company_name' => $companyData['company_name'],
            ]
        );

        DonationNote::create([
            'donationId' => $donation->id,
            'content' => esc_html__('Donation information added to Double the Donation 360MatchPro', 'give-double-the-donation')
        ]);
    }

    /**
     * Remove payment meta added on donation confirmation page
     *
     * @since 2.1.0
     */
    public function remove(Donation $donation)
    {
        $fields = [
            'company_id',
            'company_name',
            'entered_text'
        ];

        foreach ($fields as $name => $value) {
            give_delete_meta(
                $donation->id,
                'doublethedonation_' . $name,
            );
        }

        give_delete_meta(
            $donation->id,
            '_give_donation_company',
        );

        give()->donor_meta->delete_meta(
            $donation->donorId,
            '_give_donor_company',
        );
    }
}
