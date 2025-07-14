<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Helper class responsible for loading add-on assets.
 *
 * @package     GiveDoubleTheDonation\Addon
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Assets
{

    /**
     * Load add-on backend assets.
     *
     * @since 1.0.0
     * @return void
     */
    public static function loadBackendAssets()
    {
        $assets = require(GIVE_DTD_DIR . 'build/backend.asset.php');

        wp_enqueue_script(
            'give-double-the-donation-script-backend',
            GIVE_DTD_URL . 'build/backend.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );
    }

    /**
     * Load add-on front-end assets.
     *
     * @since 1.0.0
     * @return void
     */
    public static function loadFrontendAssets()
    {
        $assets = require(GIVE_DTD_DIR . 'build/frontend.asset.php');

        $assets['dependencies'][] = 'give-double-the-donation-script';

        wp_enqueue_script(
            'give-double-the-donation-script',
            'https://doublethedonation.com/api/js/ddplugin.js',
            []
        );

        wp_enqueue_style(
            'give-double-the-donation-style',
            'https://doublethedonation.com/api/css/ddplugin.css',
            []
        );

		wp_enqueue_script(
			'give-double-the-donation-script-frontend',
            GIVE_DTD_URL . 'build/frontend.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );
    }

    /**
     * @unreleased
     */
    public function loadReceiptScripts()
    {
        if (!$dtdPublicKey = give_get_option('public_dtd_key')) {
            $this->loadFrontendAssets();

            wp_register_script('givewp-dtd-receipt-script', null);
            wp_add_inline_script(
                'givewp-dtd-receipt-script',
                sprintf("var DDCONF = {'API_KEY': '%s', 'GIVE_ENDPOINT': '%s'}", $dtdPublicKey, rest_url('givewp/dtd/donation/')),
                'before'
            );
            wp_enqueue_script('givewp-dtd-receipt-script', null, ['give-double-the-donation-script']);
        }
    }
}
