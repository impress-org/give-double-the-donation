<?php
namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider;

use GiveDoubleTheDonation\Addon\Activation;
use GiveDoubleTheDonation\Addon\License;
use GiveDoubleTheDonation\Addon\Language;
use GiveDoubleTheDonation\Addon\ActivationBanner;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\SettingsPage;
use GiveDoubleTheDonation\DoubleTheDonation\SettingsPage as DoubleTheDonationSettingsPage;

/**
 * Example of a service provider responsible for add-on initialization.
 *
 * @package     GiveDoubleTheDonation\Addon
 * @copyright   Copyright (c) 2020, GiveWP
 */
class AddonServiceProvider implements ServiceProvider {
	/**
	 * @inheritDoc
	 */
	public function register() {
		give()->singleton( Activation::class );
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		// Load add-on translations.
		Hooks::addAction( 'init', Language::class, 'load' );
		Hooks::addAction( 'give_donation_form_user_info', DonationForm::class, 'employerMatchField' );

		Hooks::addAction( 'give_insert_payment', Payment::class, 'addPaymentMeta', 10, 2  );
		Hooks::addAction( 'give_insert_payment', Payment::class, 'addDonationToDTD', 11, 2 );

		// Show Receipt info
		Hooks::addAction( 'give_payment_receipt_after', UpdateDonationReceipt::class, 'renderLegacyRow', 10, 2 );
		Hooks::addAction( 'give_new_receipt', UpdateDonationReceipt::class, 'renderRowSequoiaTemplate' );

		Hooks::addFilter( 'give_metabox_form_data_settings', SettingsDonationForm::class, 'addSettings' );

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
	private function loadBackend() {

		Hooks::addAction( 'admin_init', License::class, 'check' );
		Hooks::addAction( 'admin_init', ActivationBanner::class, 'show' );
		// Load backend assets.
		Hooks::addAction( 'admin_enqueue_scripts', Assets::class, 'loadBackendAssets' );

		// Register settings page
		SettingsPage::registerPage( DoubleTheDonationSettingsPage::class );

		//Hooks::addFilter('plugin_action_links_' . GIVE_DTD_BASENAME, DoubleTheDonationSettingsPage::class, 'add_settings_link' );

		// Will display html of the import donation.
		//Hooks::addAction('give_admin_field_dtd_intro',DoubleTheDonationSettingsPage::class, 'render_intro');

	}

	/**
	 * Load add-on front-end assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function loadFrontend() {
		// Load front-end assets.
		Hooks::addAction( 'wp_enqueue_scripts', Assets::class, 'loadFrontendAssets' );
	}
}
