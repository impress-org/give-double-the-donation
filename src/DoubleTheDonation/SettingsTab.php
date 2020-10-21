<?php
namespace GiveDoubleTheDonation\DoubleTheDonation;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\SettingsPage as SettingsRegister;

class SettingsTab  {

	public function addTab(){

		// Add new settings page section.
		SettingsRegister::addPageSection( 'general', 'double-the-donation', 'Double the Donation' );

		// Add page settings.
		SettingsRegister::addSettings(
			'general',
			'double-the-donation',
			[
				[
					'name' => esc_html__( '', 'give-double-the-donation' ),
					'desc' => '',
					'id'   => 'dtd_title',
					'type' => 'title',
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
					'id'   => 'public_dtd_key',
					'type' => 'api_key',
				],
				[
					'id'   => 'dtd_setting',
					'type' => 'sectionend',
				],
			]
		);
	}

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
