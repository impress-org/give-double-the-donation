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

                         foreach (['company_id', 'company_name', 'entered_text'] as $name) {
                             give_update_meta($donation->id, 'doublethedonation_' . $name, $field->get($name));
                         }

                         // Update our core "Company Name" field.
                         give_update_meta($donation->id, '_give_donation_company', $field->get('company_name'));

                         Give()->donor_meta->update_meta($donation->donor->id, '_give_donor_company', $field->get('company_name'));
                    }
                );
        });
    }

    /**
     * @unreleased
     */
    private function setAttributes(DoubleTheDonationField $field, BlockModel $block): void
    {
        foreach (['company_id', 'company_name', 'entered_text'] as $name) {
            $field->set($name, $block->getAttribute($name));
        }
    }
}
