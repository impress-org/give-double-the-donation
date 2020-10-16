<?php
namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Helper class responsible for loading add-on assets.
 *
 * @package     GiveDoubleTheDonation\Addon
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Assets {

	/**
	 * Load add-on backend assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function loadBackendAssets() {

	}

	/**
	 * Load add-on front-end assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function loadFrontendAssets() {

		wp_enqueue_script(
			'give-double-the-donation-style-script',
			'https://doublethedonation.com/api/js/ddplugin.js',
			[]
		);
		wp_enqueue_style(
			'give-double-the-donation-style',
			'https://doublethedonation.com/api/css/ddplugin.css',
			[]
		);


	}
}
