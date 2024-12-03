<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions;

use Give\Donations\Models\Donation;
use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Contracts\Node;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveDoubleTheDonation\Addon\View;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\FieldScope\PaymentMeta;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Field as DoubleTheDonationField;
use GiveDoubleTheDonation\DoubleTheDonation\Helpers\DoubleTheDonationApi;

/**
 * @since 2.0.0
 */
class ConvertBlockToField
{
    /**
     * @since 2.0.0
     * @throws EmptyNameException
     */
    public function __invoke(?Node $node, BlockModel $block, int $blockIndex, int $formId): ?Node
    {
        if ( ! give(DoubleTheDonationApi::class)->isKeyValid()) {
            return null;
        }

        return DoubleTheDonationField::make('dtd')
            ->showInReceipt()
            ->receiptLabel(__('Company Matching', 'give-double-the-donation'))
            ->receiptValue(function(DoubleTheDonationField $field, Donation $donation) {
                return View::load('dtd-receipt', [
                    'donation' => $donation,
                ]);
            })
            ->tap(function (DoubleTheDonationField $field) use ($block) {
                $field
                    ->label($block->getAttribute('label'))
                    ->scope((new PaymentMeta)());
            });
    }
}
