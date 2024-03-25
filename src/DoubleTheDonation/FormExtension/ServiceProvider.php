<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as ServiceProviderInterface;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\ConvertBlockToField;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\DisplayFieldLabel;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\InsertDefaultBlock;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\LoadAssets;

/**
 * @unreleased
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
        Hooks::addAction('givewp_form_builder_new_form', InsertDefaultBlock::class);

        Hooks::addFilter('givewp_donation_form_block_render_givewp/dtd', ConvertBlockToField::class, '__invoke', 10, 4);
        Hooks::addFilter('givewp_donation_confirmation_page_field_label_for_dtd', DisplayFieldLabel::class, '__invoke', 10, 3);
    }
}
