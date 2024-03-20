<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Framework\FieldsAPI\Field as FieldsApiField;

/**
 * @unreleased
 */
class Field extends FieldsApiField
{
    const TYPE = 'dtd';

    protected $label;

    /**
     * @unreleased
     */
    public function setLabel(?string $label)
    {
        $this->label = $label;
    }

    /**
     * @unreleased
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Get prop names of the data attribute
     *
     * @unreleased
     */
    public function getDataAttributeProps(): array
    {
        return [
            'company_id',
            'company_name',
            'entered_text'
        ];
    }

    /**
     * Get prefixed meta key
     *
     * @unreleased
     */
    public function getKey(string $key): string
    {
        return 'doublethedonation_' . $key;
    }
}
