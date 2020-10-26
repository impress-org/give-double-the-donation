<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\SettingsPage as SettingsRegister;

class SettingsTab {

	public function addTab() {

		// Add new settings page section.
		SettingsRegister::addPageSection( 'general', 'double-the-donation', 'Double the Donation' );

		// Add page settings.
		SettingsRegister::addSettings(
			'general',
			'double-the-donation',
			[
				[
					'name' => '',
					'id'   => 'dtd_title',
					'type' => 'title',
				],
				[
					'name' => '',
					'id'   => 'dtd_intro',
					'type' => 'dtd_intro',
				],
				[
					'name' => esc_html__( 'Public API Key', 'give-double-the-donation' ),
					'desc' => esc_html__( 'Please enter the PUBLIC API key from Double the Donation.', 'give-double-the-donation' ),
					'id'   => 'public_dtd_key',
					'type' => 'api_key',
				],
				[
					'name' => esc_html__( 'Private API Key', 'give-double-the-donation' ),
					'desc' => esc_html__( 'Please enter the PRIVATE API key from Double the Donation.', 'give-double-the-donation' ),
					'id'   => 'private_dtd_key',
					'type' => 'api_key',
				],
				[
					'name'  => esc_html__( 'Documentation', 'give' ),
					'id'    => 'dtd_docs_link',
					'url'   => esc_url( 'http://docs.givewp.com/double-the-donation' ),
					'title' => esc_html__( 'Documentation', 'give' ),
					'type'  => 'give_docs_link',
				],
				[
					'id'   => 'dtd_setting',
					'type' => 'sectionend',
				],
			]
		);
	}

	public function renderIntro() { ?>

		<div style="max-width: 600px; margin: 20px 0 25px;">
			<img src="<?php echo GIVE_DTD_URL . '/public/images/dtd-logo.png'; ?>" width="400" />

			<p>Seamlessly integrate the
				<a href="https://doublethedonation.com" target="_blank">Double the Donation</a> database of corporate matching gift and volunteer grant programs with your GiveWP donation forms. Don't have an account with Double the Donation yet?
				<a href="https://zfrmz.com/ciL5Qb5coNEPElvUnYpu" target="_blank">Click here to get started!</a></p>
		</div>

	<?php }

	/**
	 * Add Settings Link tab to plugin row.
	 *
	 * @param $actions
	 *
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

}
