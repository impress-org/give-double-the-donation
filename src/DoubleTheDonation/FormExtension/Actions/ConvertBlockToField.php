<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Contracts\Node;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;


/**
 * @unreleased
 */
class ConvertBlockToField
{
    /**
     * @unreleased
     * @throws EmptyNameException
     */
    public function __invoke(?Node $node, BlockModel $block, int $blockIndex, int $formId): Node
    {
        return DoubleTheDonationField::make('dtd')
            ->tap(function (DoubleTheDonationField $field) use ($block) {

            });
        //
    }

    /**
     * @unreleased
     */
    private function setAttributes(DoubleTheDonationField $field, BlockModel $block): void
    {
    }
}
