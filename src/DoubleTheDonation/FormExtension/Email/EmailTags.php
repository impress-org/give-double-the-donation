<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Email;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\Markup;

/**
 * @unreleased
 */
class EmailTags
{
    /**
     * Register email tags
     *
     * @unreleased
     */
    public function register(array $tags): array
    {
        return array_merge($tags, [
            [
                'tag'     => 'matching_company',
                'desc'    => __(
                    'Matching Company',
                    'give-double-the-donation'
                ),
                'func'    => [$this, 'handleTag'],
                'context' => 'dtd',
            ],
            [
                'tag'     => 'matching_company_guidelines',
                'desc'    => __(
                    'Matching Company Guidelines',
                    'give-double-the-donation'
                ),
                'func'    => [$this, 'handleTag'],
                'context' => 'dtd',
            ],
        ]);
    }

    /**
     * @unreleased
     */
    public function handleTag(array $args, string $tag): string
    {
        if (isset($args['payment_id'])) {
            switch ($tag) {
                case 'matching_company':
                    return $this->getCompany($args['payment_id']);
                case 'matching_company_guidelines':
                    return $this->getGuidelines($args['payment_id']);
            }
        }

        return '';
    }


    /**
     * Add preview placeholders
     *
     * @unreleased
     */
    public function preview(string $template): string
    {
        return str_ireplace(
            [
                '{matching_company}',
                '{matching_company_guidelines}',
            ],
            [
                '***matching-company***',
                '***matching-company-guidelines***',
            ],
            $template
        );
    }

    /**
     * Get matching company name
     *
     * @unreleased
     */
    public function getCompany($donationId): string
    {
        return give_get_meta($donationId, 'doublethedonation_company_name', true);
    }

    /**
     * Get matching company guidelines
     *
     * @unreleased
     */
    public function getGuidelines($donationId): string
    {
        $content = '';

        $companyId    = give_get_meta($donationId, 'doublethedonation_company_id', true);
        $instructions = give(DoubleTheDonationApi::class)->getCompanyInstructions($companyId);

        if (empty($instructions)) {
            return $content;
        }

        // If there is no guidelines or forms url, show the matching process
        if (empty($instructions['url_guidelines']) && empty($instructions['url_forms'])) {
            if ( ! empty($instructions['matching_process'])) {
                return Markup::preformatted($instructions['matching_process']);
            }
        }

        if ( ! empty($instructions['url_guidelines'])) {
            $content .= Markup::anchor($instructions['url_guidelines'], __('Match Guidelines', 'give-double-the-donation'));
        }

        if ( ! empty($instructions['url_forms'])) {
            $content .= Markup::anchor($instructions['url_forms'], __('Complete the Match', 'give-double-the-donation'));
        }

        return $content;
    }
}
