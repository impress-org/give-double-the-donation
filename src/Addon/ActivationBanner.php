<?php

namespace GiveDoubleTheDonation\Addon;

/**
 * Helper class responsible for showing add-on Activation Banner.
 *
 * @package     GiveDoubleTheDonation\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class ActivationBanner {

	/**
	 * Show activation banner
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function show() {
		// Only runs on admin.
		$args = [
			'file'              => GIVE_DTD_FILE,
			'name'              => 'Double the Donation',
			'version'           => GIVE_DTD_VERSION,
			'settings_url'      => admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=double-the-donation' ),
			'documentation_url' => 'https://docs.givewp.com/double-the-donation/',
			'support_url'       => 'https://givewp.com/support/',
			'testing'           => false, // Never leave true.
		];

		new \Give_Addon_Activation_Banner( $args );
	}
}
