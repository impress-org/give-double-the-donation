<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Helpers;

/**
 * Helper class used for handling DTD credentials
 *
 * @unreleased
 */
class Credentials
{
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
     * @return false|string
     * @unreleased
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return false|string
     * @unreleased
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Check credentials.
     *
     * @unreleased
     */
    public function check(): bool
    {
        if ( ! $this->publicKey || ! $this->privateKey) {
            return false;
        }

        $request = wp_remote_post(
            sprintf(
                'https://doublethedonation.com/api/360matchpro-partners/v1/verify-360-keys?360matchpro_public_key=%s&360matchpro_private_key=%s',
                $this->getPublicKey(),
                $this->getPrivateKey()
            )
        );

        if (is_wp_error($request)) {
            return false;
        }

        $response = json_decode(wp_remote_retrieve_body($request));

        return $response->public_key_valid;
    }
}


