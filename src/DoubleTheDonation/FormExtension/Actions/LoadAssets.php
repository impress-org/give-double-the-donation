<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @unreleased
 */
class LoadAssets
{
    /**
     * @var DoubleTheDonationApi
     */
    private $api;

    /**
     * @param DoubleTheDonationApi $api
     */
    public function __construct(DoubleTheDonationApi $api)
    {
        $this->api = $api;
    }

    /**
     * Load Form builder block
     *
     * @unreleased
     */
    public function formBuilder(): void
    {
        $assets = require(GIVE_DTD_DIR . 'build/block.asset.php');

        wp_enqueue_script(
            'givewp-form-extension-dtd-block',
            GIVE_DTD_URL . 'build/block.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );

        wp_localize_script(
            'givewp-form-extension-dtd-block',
            'GiveDTD',
            [
                'isApiKeyValid' => $this->api->isKeyValid(),
                'settingsPage' => admin_url('edit.php?post_type=give_forms&page=give-settings&tab=give-double-the-donation')
            ]
        );

        wp_enqueue_style(
            'givewp-form-extension-dtd-block',
            GIVE_DTD_URL . 'build/block.css',
            [],
            $assets['version']
        );
    }

    /**
     * Load donation form template
     *
     * @unreleased
     */
    public function donationForm(): void
    {
        $assets = require(GIVE_DTD_DIR . 'build/template.asset.php');

        wp_enqueue_script(
            'givewp-form-extension-dtd-template',
            GIVE_DTD_URL . 'build/template.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );

        wp_enqueue_style(
            'givewp-form-extension-dtd-template',
            GIVE_DTD_URL . 'build/template.css',
            [],
            $assets['version']
        );

        wp_localize_script(
            'givewp-form-extension-dtd-template',
            'GiveDTD',
            [
                'isApiKeyValid' => $this->api->isKeyValid(),
                'searchEndpoint' => 'https://doublethedonation.com/api/360matchpro-partners/v1/search_by_company_prefix',
                'settingsPage' => admin_url('edit.php?post_type=give_forms&page=give-settings&tab=give-double-the-donation')
            ]
        );
    }
}
