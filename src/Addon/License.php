<?php

namespace GiveDoubleTheDonation\Addon;

class License {

	/**
	 * Check add-on license.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function check() {
		new \Give_License(
			GIVE_DTD_FILE,
			GIVE_DTD_NAME,
			GIVE_DTD_VERSION,
			'GiveWP'
		);
	}
}
