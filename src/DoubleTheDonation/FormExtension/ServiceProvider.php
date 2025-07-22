<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as ServiceProviderInterface;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\ConvertBlockToField;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\DisplayField;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\LoadAssets;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Email\EmailTags;

/**
 * @since 2.0.0
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        Hooks::addAction('givewp_form_builder_enqueue_scripts', LoadAssets::class, 'formBuilder');
        Hooks::addAction('givewp_donation_form_enqueue_scripts', LoadAssets::class, 'donationForm');

        Hooks::addFilter('givewp_donation_form_block_render_givewp/dtd', ConvertBlockToField::class, '__invoke', 10, 4);
        Hooks::addFilter('givewp_donation_confirmation_page_field_value_for_dtd', DisplayField::class, 'value', 10, 3);

        Hooks::addFilter('give_email_tags', EmailTags::class, 'register');
        Hooks::addFilter('give_email_preview_template_tags', EmailTags::class, 'preview');
    }
}
