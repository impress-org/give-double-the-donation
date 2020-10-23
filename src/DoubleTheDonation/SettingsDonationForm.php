<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

class SettingsDonationForm {

	/**
	 * Add metabox form settings for Double the Donation.
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	function addSettings( $settings ) {

		$settings['dtd_options'] = [
			'id'        => 'dtd_options',
			'title'     => esc_html__( 'Company Matching', 'give-double-the-donation' ),
			'icon-html' => '<i class="fas fa-building"></i>',
			'fields'    => [
				[
					'name'    => esc_html__( 'Employer Search', 'give-double-the-donation' ),
					'id'      => 'dtd_enable_disable',
					'type'    => 'radio_inline',
					'desc'    => esc_html__( 'Do you want to add the Double the Donation employer search field to this donation form?',
						'give-double-the-donation' ),
					'options' => [
						'enabled' => esc_html__( 'Enable', 'give-double-the-donation' ),
						'disabled'   => esc_html__( 'Disable', 'give-double-the-donation' ),
					],
					'default' => 'disabled',
				],
				[
					'id'            => 'give_dtd_label',
					'name'          => esc_html__( 'Default Label', 'give-double-the-donation' ),
					'desc'          => esc_html__( 'This is the text shown by default above the employer search field.',
						'give-double-the-donation' ),
					'type'          => 'text',
					'default'       => esc_html__( 'See if your company will match your donation!', 'give-double-the-donation' ),
					'wrapper_class' => 'give-dtd-metabox-field',
				],
				[
					'name'  => esc_html__( 'Double the Donation Docs Link', 'give-double-the-donation' ),
					'id'    => 'dtd_settings_docs_link',
					'url'   => esc_url( 'http://docs.givewp.com/addon-dtd' ),
					'type'  => 'give_docs_link',
				],
			],
		];

		return $settings;

	}

}
