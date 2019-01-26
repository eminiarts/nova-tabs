<?php
namespace Eminiarts\Tabs;

use RuntimeException;
use Laravel\Nova\Panel;
use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Contracts\ListableField;

class Tabs extends Panel
{
    /**
     * @var mixed
     */
    public $defaultSearch = false;

    /**
     * Add fields to the Tab.
     *
     * @param string $tab
     * @param array  $fields
     */
    public function addFields($tab, array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof ListableField || $field instanceof Panel) {
                $this->addTab($field);
                continue;
            }
            if ($field instanceof MergeValue) {
                $this->addFields($tab, $field->data);
                continue;
            }
            $field->panel = $this->name;
            $field->withMeta([
                'tab' => $tab,
            ]);
            $this->data[] = $field;
        }

        return $this;
    }

    /**
     * Add a new Tab
     */
    public function addTab($panel): self
    {
        if ($panel instanceof ListableField) {
            $panel->panel = $this->name;
            $panel->withMeta([
                'tab'         => $panel->name,
                'listable'    => false,
                'listableTab' => true,
            ]);
            $this->data[] = $panel;
        } elseif ($panel instanceof Panel) {
            $this->addFields($panel->name, $panel->data);
        } else {
            throw new RuntimeException('Only listable fields or Panel allowed.');
        }

        return $this;
    }

    /**
     * Show default Search if you need more space
     */
    public function defaultSearch($value = true)
    {
        $this->defaultSearch = $value;

        return $this;
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'component'     => 'detail-tabs',
            'defaultSearch' => $this->defaultSearch,
        ]);
    }

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        collect(is_callable($fields) ? $fields() : $fields)->each(function ($fields, $key) {
            if (is_string($key) && is_array($fields)) {
                $fields = new Panel($key, $fields);
            }
            $this->addTab($fields);
        });

        return $this->data;
    }
}
