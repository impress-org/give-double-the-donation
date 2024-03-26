<?php

namespace GiveDoubleTheDonation\Addon;

/**
 * Helper class responsible for showing add-on notices.
 *
 * @package     GiveDoubleTheDonation\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class Notices {

	/**
	 * GiveWP min required version notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function giveVersionError() {
		Give()->notices->register_notice(
			[
				'id'          => 'give-double-the-donation-activation-error',
				'type'        => 'error',
				'description' => View::load( 'admin/notices/give-version-error' ),
				'show'        => true,
			]
		);
	}

	/**
	 * GiveWP inactive notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function giveInactive() {
		echo View::load( 'admin/notices/give-inactive' );
	}


    /**
     * DTD invalid credentials
     *
     * @unreleased
     */
    public static function invalidCredentials() {
        echo View::load( 'admin/notices/credentials-error' );
    }
}
