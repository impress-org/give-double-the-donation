<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Actions;

use GiveDoubleTheDonation\Addon\Notices;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\Credentials;

/**
 * Action used to check DTD credentials when DTD settings are saved
 * @unreleased
 */
class CheckCredentials
{
    public function __invoke()
    {
        $credentials = give(Credentials::class);

        if ( ! $credentials->check()) {
            Notices::invalidCredentials();
        }
    }
}
