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
		wp_enqueue_style(
			'give-double-the-donation-style-backend',
			GIVE_DTD_URL . 'public/css/give-double-the-donation-admin.css',
			[],
			GIVE_DTD_VERSION
		);

		wp_enqueue_script(
			'give-double-the-donation-script-backend',
			GIVE_DTD_URL . 'public/js/give-double-the-donation-admin.js',
			[],
			GIVE_DTD_VERSION,
			true
		);
	}

	/**
	 * Load add-on front-end assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function loadFrontendAssets() {
		wp_enqueue_style(
			'give-double-the-donation-style-frontend',
			GIVE_DTD_URL . 'public/css/give-double-the-donation.css',
			[],
			GIVE_DTD_VERSION
		);

		wp_enqueue_script(
			'give-double-the-donation-script-frontend',
			GIVE_DTD_URL . 'public/js/give-double-the-donation.js',
			[],
			GIVE_DTD_VERSION,
			true
		);
	}
}
