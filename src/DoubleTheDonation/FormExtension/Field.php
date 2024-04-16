<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Framework\FieldsAPI\Concerns\HasLabel;
use Give\Framework\FieldsAPI\Field as FieldsApiField;

/**
 * @since 2.0.0
 */
class Field extends FieldsApiField
{
    const TYPE = 'dtd';

    use HasLabel;
}
