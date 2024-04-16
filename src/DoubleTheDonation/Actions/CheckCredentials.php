<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use GiveDoubleTheDonation\Addon\Notices;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * Action used to check DTD credentials when DTD settings are saved
 * @since 2.0.0
 */
class CheckCredentials
{
    public function __invoke()
    {
        if ( ! give(DoubleTheDonationApi::class)->checkKey()) {
            Notices::invalidCredentials();
        }
    }
}
