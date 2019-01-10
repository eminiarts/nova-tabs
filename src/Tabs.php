<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Field;

class Tabs extends Panel implements \JsonSerializable
{
    /**
     * @var string
     */
    public $activeTab = '';

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
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->data = $this->tabs;

        //dd($this);

        return [
            'component'   => 'tabs',
            'name'        => $this->name,
            'showToolbar' => $this->showToolbar,
            'tabs'        => $this->tabs,
        ];
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
        $this->showToolbar = true;

        return $this;
    }
}
