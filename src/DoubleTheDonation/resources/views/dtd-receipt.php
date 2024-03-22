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

$getProcess = function ($process) {
    if ( ! empty($process)) {
        return sprintf('<pre class="" style="text-align:left; padding:20px; max-height:250px; overflow-y:auto; border:1px solid #f2f2f2;">%s</pre>', $process) . PHP_EOL;
    }
};

if (empty($instructions['url_guidelines']) && empty($instructions['url_forms'])) {
    echo $getProcess($instructions['matching_process']);
} else {
    echo $getLink($instructions['url_guidelines'], __('Match Guidelines', 'give-double-the-donation'));
    echo $getLink($instructions['url_forms'], __('Complete the Match', 'give-double-the-donation'));
}

