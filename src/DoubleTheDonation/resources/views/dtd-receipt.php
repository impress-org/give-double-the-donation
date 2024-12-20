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
        '<p style="margin-bottom: 0;"><a href="%s" target="_blank">%s</a></p>',
        $instructions['url_guidelines'],
        __('Match Guidelines', 'give-double-the-donation')
    ) . PHP_EOL;
}
if ( ! empty($instructions['url_forms'])) {
    printf(
        '<p style="margin-bottom: 0;"><a href="%s" target="_blank">%s</a></p>',
        $instructions['url_forms'],
        __('Complete the Match', 'give-double-the-donation')
    );
}

