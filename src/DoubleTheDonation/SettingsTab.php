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
					'id'   => 'text_field_setting',
					'type' => 'sectionend',
				],
			]
		);
	}

}
