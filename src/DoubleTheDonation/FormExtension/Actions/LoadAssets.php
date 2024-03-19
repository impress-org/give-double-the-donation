<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\Credentials;

/**
 * @unreleased
 */
class LoadAssets
{
    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Load Form builder block
     *
     * @unreleased
     */
    public function formBuilder(): void
    {
        wp_enqueue_script(
            'givewp-form-extension-dtd-block',
            GIVE_DTD_URL . 'build/block.js',
            [],
            GIVE_DTD_VERSION,
            true
        );

        wp_localize_script(
            'givewp-form-extension-dtd-block',
            'GiveDTD',
            [
                'isApiKeyValid' => $this->credentials->isApiKeyValid(),
            ]
        );
    }

    /**
     * Load donation form template
     *
     * @unreleased
     */
    public function donationForm(): void
    {
        wp_enqueue_script(
            'givewp-form-extension-dtd-template',
            GIVE_DTD_URL . 'build/template.js',
            [],
            GIVE_DTD_VERSION,
            true
        );

        wp_enqueue_style(
            'givewp-form-extension-dtd-template',
            GIVE_DTD_URL . 'build/template.css'
        );

        wp_localize_script(
            'givewp-form-extension-dtd-template',
            'GiveDTD',
            [
                'isApiKeyValid' => $this->credentials->isApiKeyValid(),
            ]
        );
    }
}
