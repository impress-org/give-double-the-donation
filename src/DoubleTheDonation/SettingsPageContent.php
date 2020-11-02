<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Class SettingsPageContent
 * @package GiveDoubleTheDonation\DoubleTheDonation
 *
 * The methods must be included on a separate class than the SettingsPage class because the Give_Settings_Page class is
 * only loaded by GiveWP core in specific contexts. Attempting to move these methods to that class will cause the hooks
 * to break.
 */
class SettingsPageContent {
	/**
	 * Renders the top of the settings page
	 *
	 * @since 1.0.0
	 */
	public function renderIntro() {
		$logo_url = GIVE_DTD_URL . '/public/images/dtd-logo.png';

		echo /** @lang HTML */ "
			<div style='max-width: 600px; margin: 20px 0 25px;'>
				<img src='{$logo_url}' alt='Double the Donations Logo' width='400' />

				<p>Seamlessly integrate the
					<a href='https://doublethedonation.com' target='_blank'>Double the Donation</a> database of corporate matching gift and volunteer grant programs with your GiveWP donation forms. Don't have an account with Double the Donation yet?
					<a href='http://docs.givewp.com/dtd-get-started' target='_blank'>Click here to get started!</a></p>
			</div>
		";
	}

	/**
	 * Add Settings Link tab to plugin row.
	 *
	 * @param $actions
	 *
	 * @return array
	 */
	public function addSettingsLink( $actions ) {
		$new_actions = [
			'settings' => sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=double-the-donation' ),
				__( 'Settings', 'give-double-the-donation' )
			),
		];

		return array_merge( $new_actions, $actions );
	}

}
