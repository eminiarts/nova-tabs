<?php

namespace Eminiarts\Tabs;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\FieldCollection as NovaFieldCollection;

class FieldCollection extends NovaFieldCollection
{
    /**
     * Retrieve the field match matches the instance.
     *
     * @param  string  $type
     * @return \Eminiarts\Tabs\FieldCollection
     */
    public function whereInstanceOf($type)
    {
        return $this->flatMap(function ($tab) use ($type) {
            return collect(Arr::get($tab, 'fields'))->whereInstanceOf($type);
        });
    }

    public function findFieldByAttribute($attribute, $default = null)
    {
        foreach ($this->items as $field) {
            if (isset($field->attribute) && $field->attribute === $attribute) {
                return $field;
            }

            // Search the fields inside tabs.
            if (is_array($field) && isset($field['component']) && $field['component'] === 'tabs') {
                return static::make($field['fields'])->findFieldByAttribute($attribute, $default);
            }
        }

        return null;
    }
}
