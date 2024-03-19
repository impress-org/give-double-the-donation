<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Donations\Models\Donation;
use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Contracts\Node;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;


/**
 * @unreleased
 */
class ConvertBlockToField
{
    /**
     * @unreleased
     * @throws EmptyNameException
     */
    public function __invoke(?Node $node, BlockModel $block, int $blockIndex, int $formId): ?Node
    {
        if ( ! give(DoubleTheDonationApi::class)->isKeyValid()) {
            return null;
        }

        return DoubleTheDonationField::make('dtd')
            ->tap(function (DoubleTheDonationField $field) use ($block) {

                $this->setAttributes($field, $block);

                $field->scope(
                    function (DoubleTheDonationField $field, $value, Donation $donation) {

                    }
                );
            });
    }

    /**
     * @unreleased
     */
    private function setAttributes(DoubleTheDonationField $field, BlockModel $block): void
    {
    }
}
