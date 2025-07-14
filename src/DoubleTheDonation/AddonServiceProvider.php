<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider;
use GiveDoubleTheDonation\Addon\Activation;
use GiveDoubleTheDonation\Addon\ActivationBanner;
use GiveDoubleTheDonation\Addon\Language;
use GiveDoubleTheDonation\Addon\License;
use GiveDoubleTheDonation\DoubleTheDonation\Actions\CheckCredentials;
use GiveDoubleTheDonation\DoubleTheDonation\API\REST\CompanyMatching;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\SettingsPage as SettingsPageRegister;

/**
 * Example of a service provider responsible for add-on initialization.
 *
 * @package     GiveDoubleTheDonation\Addon
 * @copyright   Copyright (c) 2020, GiveWP
 */
class AddonServiceProvider implements ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        give()->singleton(Activation::class);
        give()->singleton(SettingsPageContent::class);
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        // Load add-on translations.
        Hooks::addAction('init', Language::class, 'load');
        Hooks::addAction('give_donation_form_after_email', DonationForm::class, 'employerMatchField');

        // handle v2 forms stuff
        if (isset($_POST) && ! isset($_POST['dtd'])) {
            Hooks::addAction('give_insert_payment', Payment::class, 'addPaymentMeta', 10, 2);
            Hooks::addAction('give_insert_payment', Payment::class, 'addDonationToDTD', 11, 2);
        }

        // Show Receipt info
        Hooks::addAction('give_payment_receipt_after', UpdateDonationReceipt::class, 'renderLegacyRow', 10, 2);
        Hooks::addAction('give_new_receipt', UpdateDonationReceipt::class, 'renderRowSequoiaTemplate');

        Hooks::addFilter('give_metabox_form_data_settings', SettingsDonationForm::class, 'addSettings');

        is_admin()
            ? $this->loadBackend()
            : $this->loadFrontend();
    }


    /**
     * Load add-on backend assets.
     *
     * @since 1.0.0
     * @return void
     */
    private function loadBackend()
    {
        Hooks::addAction('admin_init', License::class, 'check');
        Hooks::addAction('admin_init', ActivationBanner::class, 'show');
        // Load backend assets.
        Hooks::addAction('admin_enqueue_scripts', Assets::class, 'loadBackendAssets');

        // Register settings page
        SettingsPageRegister::registerPage(SettingsPage::class);

        Hooks::addFilter('plugin_action_links_' . GIVE_DTD_BASENAME, SettingsPageContent::class, 'addSettingsLink');

        // Will display html of the import donation.
        Hooks::addAction('give_admin_field_dtd_intro', SettingsPageContent::class, 'renderIntro');

        // Check DTD credentials on settings save
        Hooks::addAction('give-settings_save_give-double-the-donation', CheckCredentials::class, '__invoke', 100);
    }

    /**
     * Load add-on front-end assets.
     *
     * @since 1.0.0
     * @return void
     */
    private function loadFrontend()
    {
        // @unreleased
        Hooks::addAction('rest_api_init', CompanyMatching::class);

        // Load front-end assets.
        Hooks::addAction('wp_enqueue_scripts', Assets::class, 'loadFrontendAssets');
        Hooks::addAction('givewp_donation_confirmation_receipt_showing', Assets::class, 'loadReceiptScripts');
    }
}
