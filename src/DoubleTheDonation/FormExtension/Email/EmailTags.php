<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Email;

use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @since 2.0.0
 */
class EmailTags
{
    /**
     * Register email tags
     *
     * @since 2.0.0
     */
    public function register(array $tags): array
    {
        return array_merge($tags, [
            [
                'tag'     => 'matching_company',
                'desc'    => __(
                    'Double the Donation Matching Company',
                    'give-double-the-donation'
                ),
                'func'    => [$this, 'handleTag'],
                'context' => 'dtd',
            ],
            [
                'tag'     => 'matching_company_guidelines',
                'desc'    => __(
                    'Double the Donation Matching Company Guidelines',
                    'give-double-the-donation'
                ),
                'func'    => [$this, 'handleTag'],
                'context' => 'dtd',
            ],
        ]);
    }

    /**
     * @since 2.0.0
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
     * @since 2.0.0
     */
    public function preview(string $template): string
    {
        return str_replace(
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
     * @since 2.0.0
     */
    public function getCompany($donationId): string
    {
        return give_get_meta($donationId, 'doublethedonation_company_name', true);
    }

    /**
     * Get matching company guidelines
     *
     * @since 2.0.0
     */
    public function getGuidelines($donationId): string
    {
        $content = '';

        $companyId    = give_get_meta($donationId, 'doublethedonation_company_id', true);
        $instructions = give(DoubleTheDonationApi::class)->getCompanyInstructions($companyId);

        if (empty($instructions)) {
            return $content;
        }

        if ( ! empty($instructions['url_guidelines'])) {
            $content .= sprintf(
                            '<a href="%s" target="_blank">%s</a>',
                            $instructions['url_guidelines'],
                            __('Match Guidelines', 'give-double-the-donation')
                        ) . PHP_EOL;
        }

        if ( ! empty($instructions['url_forms'])) {
            $content .= sprintf(
                '<a href="%s" target="_blank">%s</a>',
                $instructions['url_forms'],
                __('Complete the Match', 'give-double-the-donation')
            );
        }

        return $content;
    }
}
