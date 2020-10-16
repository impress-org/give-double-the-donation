<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Example code to show how to add setting page to give settings.
 */
class SettingsPage extends \Give_Settings_Page {

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id          = 'give-double-the-donation';
		$this->label       = esc_html__( 'Double the Donation ', 'give-double-the-donation' );
		$this->default_tab = 'dtd_fields';

		parent::__construct();
	}


	/**
	 * Add setting sections.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sections() {
	}


	/**
	 * Get setting.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_settings() {

		/**
		 * Default settings
		 */
		return [


		];

	}
}
