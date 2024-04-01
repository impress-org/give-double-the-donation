<?php

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @var Donation $donation
 */

$companyId = give_get_meta($donation->id, 'doublethedonation_company_id', true);

if ( ! $companyId) {
    return;
}

$instructions = give(DoubleTheDonationApi::class)->getCompanyInstructions($companyId);

if (empty($instructions)) {
    return;
}

if ( ! empty($instructions['url_guidelines'])) {
    printf(
        '<a href="%s" target="_blank">%s</a>',
        $instructions['url_guidelines'],
        __('Match Guidelines', 'give-double-the-donation')
    ) . PHP_EOL;
}
if ( ! empty($instructions['url_forms'])) {
    printf(
        '<a href="%s" target="_blank">%s</a>',
        $instructions['url_forms'],
        __('Complete the Match', 'give-double-the-donation')
    );
}

