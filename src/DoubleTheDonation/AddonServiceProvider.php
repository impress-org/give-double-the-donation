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


		add_filter('plugin_action_links_' . GIVE_DTD_BASENAME, [$this, 'addSettingsLink']);
		add_action('give_admin_field_dtd_intro', [$this, 'renderIntro']);
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

	/**
	 * Add Settings Link tab to plugin row.
	 *
	 * @param $actions
	 * @since 1.0.0
	 * @return array
	 */
	public function addSettingsLink( $actions ) {
		$new_actions = array(
			'settings' => sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=double-the-donation' ),
				__( 'Settings', 'give-double-the-donation' )
			),
		);

		return array_merge( $new_actions, $actions );
	}

	/**
	 * Render intro
	 *
	 * @since 1.0.0
	 * @return void
	 */
    public function renderIntro() { ?>

		<div style="max-width: 600px; margin: 20px 0 25px;">
			<img src="<?php echo GIVE_DTD_URL . '/public/images/dtd-logo.png'; ?>" width="400" />

			<p>Seamlessly integrate the
				<a href="https://doublethedonation.com" target="_blank">Double the Donation</a> database of corporate matching gift and volunteer grant programs with your GiveWP donation forms. Don't have an account with Double the Donation yet?
				<a href="http://docs.givewp.com/dtd-get-started" target="_blank">Click here to get started!</a></p>
		</div>

    <?php }
}
