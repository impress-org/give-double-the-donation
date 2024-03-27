<?php

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @var Donation $donation
 */

$companyId = give_get_meta($donation->id, 'doublethedonation_company_id', true);

$instructions = give(DoubleTheDonationApi::class)->getCompanyInstructions($companyId);

if (empty($instructions)) {
    return;
}

$getLink = function ($url, $text) {
    if ( ! empty($url)) {
       return sprintf('<a href="%s" target="_blank">%s</a>', $url, $text) . PHP_EOL;
    }
};

echo $getLink($instructions['url_guidelines'], __('Match Guidelines', 'give-double-the-donation'));
echo $getLink($instructions['url_forms'], __('Complete the Match', 'give-double-the-donation'));
