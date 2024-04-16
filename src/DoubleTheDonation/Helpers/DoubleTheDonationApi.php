<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Helpers;

/**
 * Helper class used for handling DTD credentials
 *
 * @since 2.0.0
 */
class DoubleTheDonationApi
{
    /**
     * Transient key
     *
     * @since 2.0.0
     */
    private $key = 'give_dtd_api';

    /**
     * @var false|string
     */
    private $publicKey;

    /**
     * @var false|string
     */
    private $privateKey;

    /**
     * Credentials constructor.
     */
    public function __construct()
    {
        $this->publicKey  = give_get_option('public_dtd_key');
        $this->privateKey = give_get_option('private_dtd_key');
    }

    /**
     * @since 2.0.0
     */
    public function isKeyValid(): bool
    {
        $isValid = get_transient($this->key);

        if ($isValid === false) {
            return $this->checkKey();
        }

        return $isValid === 'valid';
    }

    /**
     * Check credentials.
     *
     * @since 2.0.0
     */
    public function checkKey(): bool
    {
        if ( ! $this->publicKey || ! $this->privateKey) {
            return false;
        }

        $request = wp_remote_post(
            sprintf(
                'https://doublethedonation.com/api/360matchpro-partners/v1/verify-360-keys?360matchpro_public_key=%s&360matchpro_private_key=%s',
                $this->publicKey,
                $this->privateKey
            )
        );

        if (is_wp_error($request)) {
            return false;
        }

        $response = json_decode(wp_remote_retrieve_body($request));

        set_transient(
            $this->key,
            $response->public_key_valid ? 'valid' : '',
            DAY_IN_SECONDS
        );

        return $response->public_key_valid;
    }

    /**
     * Get company match links
     *
     * @since 2.0.0
     *
     * return array{company_name: string, url_guidelines: string, url_forms: string, matching_process: string}
     */
    public function getCompanyInstructions($companyId): array
    {
        if ( ! $this->publicKey || ! $this->privateKey) {
            return [];
        }

        $request = wp_remote_get(
            sprintf(
                'https://doublethedonation.com/api/360matchpro/v1/companies/%s?key=%s',
                $companyId,
                $this->privateKey
            )
        );

        if (is_wp_error($request)) {
            return [];
        }

        $response = json_decode(wp_remote_retrieve_body($request));

        return [
            'company_name'     => $response->company_name,
            'url_guidelines'   => $response->url_guidelines,
            'url_forms'        => $response->url_guidelines === $response->url_forms ? null : $response->url_forms,
            'matching_process' => $response->matching_gift_process,
        ];
    }
}


