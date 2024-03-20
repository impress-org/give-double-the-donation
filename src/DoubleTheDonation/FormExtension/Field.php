<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension;

use Give\Framework\FieldsAPI\Field as FieldsApiField;

/**
 * @unreleased
 */
class Field extends FieldsApiField
{
    const TYPE = 'dtd';

    protected $company_id;
    protected $company_name;
    protected $entered_text;


    /**
     * Check if class property exist
     *
     * @unreleased
     */
    public function has($name): bool
    {
        return property_exists(__CLASS__, $name);
    }

    /**
     * Get property
     *
     * @unreleased
     */
    public function get(string $prop)
    {
        return $this->{$prop};
    }

    /**
     * Set property
     *
     * @unreleased
     */
    public function set(string $prop, ?string $value): Field
    {
        if ($this->has($prop)) {
            $this->{$prop} = $value;
        }

        return $this;
    }

    /**
     * Get prop names
     *
     * @unreleased
     */
    public function getPropNames(): array
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
    public function getMetaKey(string $key): string
    {
        return 'doublethedonation_' . $key;
    }
}
