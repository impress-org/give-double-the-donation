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
                $this->setFieldAttributes($field, $block);
                $this->handleFieldScope($field);
            });
    }

    /**
     * Set field attributes from block attributes
     *
     * @unreleased
     */
    private function setFieldAttributes(DoubleTheDonationField $field, BlockModel $block): void
    {
        // set props from block attributes
        $field->label($block->getAttribute('label'));
    }

    /**
     * Handle field scope
     *
     * @unreleased
     */
    private function handleFieldScope(DoubleTheDonationField $field): void
    {
        $field->scope(function (DoubleTheDonationField $field, $data, Donation $donation) {
            if (empty($data['company_id'])) {
                return;
            }

            foreach ($field->getDataAttributeProps() as $name) {
                give_update_meta(
                    $donation->id,
                    $field->getKey($name),
                    $data[$name]
                );
            }

            // Update our core "Company Name" field.
            give_update_meta(
                $donation->id,
                '_give_donation_company',
                $data['company_name']
            );

            Give()->donor_meta->update_meta(
                $donation->donorId,
                '_give_donor_company',
                $data['company_name']
            );
        });
    }
}
