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
        $block = BlockModel::make([
            'name'       => 'givewp/dtd',
            'attributes' => [
                'company_id'   => '',
                'company_name' => '',
                'entered_text' => '',
            ],
        ]);

        $form->blocks->insertAfter('givewp/donation-amount', $block);
    }
}
