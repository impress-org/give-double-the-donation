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
        $blockData = json_decode(
            file_get_contents(GIVE_DTD_DIR . 'src/DoubleTheDonation/FormExtension/Block/block.json'),
            true
        );

        $block = BlockModel::make($blockData);

        $form->blocks->insertAfter('givewp/donation-amount', $block);
    }
}
