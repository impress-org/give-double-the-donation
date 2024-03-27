<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Framework\FieldsAPI\Concerns\HasLabel;
use Give\Framework\FieldsAPI\Field as FieldsApiField;

/**
 * @unreleased
 */
class Field extends FieldsApiField
{
    const TYPE = 'dtd';

    use HasLabel;
}
