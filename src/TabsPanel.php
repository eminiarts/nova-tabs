<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Panel;
use Laravel\Nova\Resource;
use Laravel\Nova\Fields\Field;

class TabsPanel extends Panel implements \JsonSerializable
{
    /**
     * The panel fields.
     *
     * @var array
     */
    public $data;

    /**
     * The name of the panel.
     *
     * @var string
     */
    public $name;

    /**
     * Indicates whether the detail toolbar should be visible on this panel.
     *
     * @var bool
     */
    public $showToolbar = false;

    /**
     * Create a new panel instance.
     *
     * @param  string         $name
     * @param  \Closure|array $fields
     * @return void
     */
    public function __construct($name = "Tabs", $tabs = [], $showToolbar = false)
    {
        $this->name        = $name;
        $this->showToolbar = $showToolbar;
        $data              = $this->prepareFields($tabs);
        $this->data        = $data instanceof Collection ? $data->all() : $data;
    }

    /**
     * Get the default panel name for the given resource.
     *
     * @param  \Laravel\Nova\Resource $resource
     * @return string
     */
    public static function defaultNameFor(Resource $resource)
    {
        return $resource->singularLabel() . ' ' . __('Details');
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'component'   => 'tabs-panel',
            'name'        => $this->name,
            'showToolbar' => $this->showToolbar,
        ];
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

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array $fields
     * @return array
     */
    protected function prepareFields($tabs)
    {

        $tabs = collect($tabs)->each(function ($fields, $tab) {
            if ($fields instanceof Tabs) {
                return $fields->panel = $this->name;
            }

            collect(is_callable($fields) ? $fields() : $fields)->each(function ($field) use ($tab) {
                $field->panel       = $this->name;
                $field->meta['tab'] = $tab;
            })->all();
        })->flatten()->all();

        return $tabs;
    }
}
