<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Contracts\ListableField;

class Tabs extends Field implements ListableField
{
    /**
     * @var string
     */
    public $activeTab = '';

    /**
     * @var string
     */
    public $component = 'tabs';

    /**
     * The panel fields.
     *
     * @var array
     */
    public $data;

    /**
     * Indicates whether the detail toolbar should be visible on this panel.
     *
     * @var bool
     */
    public $showToolbar = false;

    /**
     * @var array
     */
    public $tabs = [];

    /**
     * @param  $name
     * @return mixed
     */
    public function activeTab($name)
    {
        $this->activeTab = $name;

        return $this;
    }

    /**
     * @param  $name
     * @param  $field
     * @param  $relationType
     * @return mixed
     */
    public function addTab($name, $fields)
    {
        $this->tabs[] = [
            'name'   => $name,
            'fields' => $fields,
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function availableFields()
    {

        return collect($this->tabs)->map(function ($item, $key) {
            is_array($item['fields']) ? collect($item['fields'])->each->hideFromDetail() : $item['fields']->hideFromDetail();

            return $item['fields'];
        });
    }

    /**
     * @param $value
     */
    public function defaultSearch()
    {
        return $this->withMeta(['extraAttributes' => [
            'defaultSearch' => true],
        ]);
    }

    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Get additional meta information to merge with the field payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'activeTab' => $this->activeTab,
            'name'      => $this->name,
            'tabs'      => $this->tabs,
            'listable'  => true,
        ], $this->meta);
    }

    /**
     * Display the toolbar when showing this panel.
     *
     * @return $this
     */
    public function withToolbar()
    {
        return $this->withMeta(['extraAttributes' => [
            'showToolbar' => true],
        ]);
    }
}
