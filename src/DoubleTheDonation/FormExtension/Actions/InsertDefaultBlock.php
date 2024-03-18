<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Blocks\BlockModel;

/**
 * @unreleased
 */
class InsertDefaultBlock
{
    public function __invoke(DonationForm $form)
    {
        // todo: update this - just a placeholder for now
        $block = BlockModel::make([
            'name'       => 'givewp/dtd',
            'attributes' => [],
        ]);

        $form->blocks->insertAfter('givewp/donation-amount', $block);
    }
}
