<?php

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\Markup;

/**
 * @var Donation $donation
 */

$companyId    = give_get_meta($donation->id, 'doublethedonation_company_id', true);
$instructions = give(DoubleTheDonationApi::class)->getCompanyInstructions($companyId);

if (empty($instructions)) {
    return;
}

if (empty($instructions['url_guidelines']) && empty($instructions['url_forms'])) {
    if ( ! empty($instructions['matching_process'])) {
        echo Markup::preformatted($instructions['matching_process']);
    }
} else {
    if ( ! empty($instructions['url_guidelines'])) {
        echo Markup::anchor($instructions['url_guidelines'], __('Match Guidelines', 'give-double-the-donation'));
    }
    if ( ! empty($instructions['url_forms'])) {
        echo Markup::anchor($instructions['url_forms'], __('Complete the Match', 'give-double-the-donation'));
    }
}

