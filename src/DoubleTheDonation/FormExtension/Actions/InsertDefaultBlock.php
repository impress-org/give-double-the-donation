<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Blocks\BlockModel;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @unreleased
 */
class InsertDefaultBlock
{
    public function __invoke(DonationForm $form)
    {
        $block = BlockModel::make([
            'name'       => 'givewp/dtd',
            'attributes' => [],
        ]);

        $form->blocks->insertAfter('givewp/donation-amount', $block);
    }
}
