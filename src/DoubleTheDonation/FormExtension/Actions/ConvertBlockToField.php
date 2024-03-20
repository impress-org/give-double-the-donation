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
        foreach ($field->getPropNames() as $name) {
            $field->set($name, $block->getAttribute($name));
        }
    }

    /**
     * Handle field scope
     *
     * @unreleased
     */
    private function handleFieldScope(DoubleTheDonationField $field): void
    {
        $field->scope(function (DoubleTheDonationField $field, $value, Donation $donation) {
            // todo: check value of company name|id?
            return false;

            foreach ($field->getPropNames() as $name) {
                give_update_meta(
                    $donation->id,
                    $field->getMetaKey($name),
                    $field->get($name)
                );
            }

            // Update our core "Company Name" field.
            give_update_meta(
                $donation->id,
                '_give_donation_company',
                $field->get('company_name')
            );

            Give()->donor_meta->update_meta(
                $donation->donorId,
                '_give_donor_company',
                $field->get('company_name')
            );
        });
    }
}
