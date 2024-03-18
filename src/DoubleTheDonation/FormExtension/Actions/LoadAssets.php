<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

/**
 * @unreleased
 */
class LoadAssets
{
    /**
     * Load Form builder block
     *
     * @unreleased
     */
    public function formBuilder(): void
    {
        wp_enqueue_script(
            'givewp-form-extension-dtd-block',
            GIVE_DTD_URL . 'build/block.js',
            [],
            GIVE_DTD_VERSION,
            true
        );
    }

    /**
     * Load donation form template
     *
     * @unreleased
     */
    public function donationForm(): void
    {
        wp_enqueue_script(
            'givewp-form-extension-dtd-template',
            GIVE_DTD_URL . 'build/template.js',
            [],
            GIVE_DTD_VERSION,
            true
        );
    }
}
