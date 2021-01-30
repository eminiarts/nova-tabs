<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Eminiarts\Tabs\Contracts\TabContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
     * @var bool
     */
    public $showTitle = false;

    /**
     * @var TabContract[]
     */
    private $tabs = [];

    /**
     * Add fields to the Tab.
     *
     * @param TabContract $tab
     * @return $this
     */
    public function addFields(TabContract $tab): self
    {
        $this->tabs[] = $tab;

        foreach ($tab->getFields() as $field) {
            if ($field instanceof Panel) {
                $this->addFields(
                    new Tab($field->name, $field->data)
                );
                continue;
            }

            if ($field instanceof MergeValue) {
                $this->addFields(
                    new Tab($tab->getTitle(), $field->data)
                );
                continue;
            }

            $field->panel = $this->name;

            $meta = [
                'tab' => $tab->getName(),
                'tabSlug' => $tab->getSlug(),
                'tabInfo' => Arr::except($tab->toArray(), [ 'fields', 'slug' ])
            ];

            if ($field instanceof ListableField) {
                $meta += [
                    'listable'    => false,
                    'listableTab' => true,
                ];
            }

            $field->withMeta($meta);

            $this->data[] = $field;
        }

        return $this;
    }

    /**
     * Show default Search if you need more space
     *
     * @param bool $value
     *
     * @return $this
     */
    public function defaultSearch(bool $value = true): self
    {
        $this->defaultSearch = $value;

        return $this;
    }

    /**
     * Whether the show the title
     *
     * @param bool $show
     * @return $this
     */
    public function showTitle($show = true): self
    {
        $this->showTitle = $show;

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
            'showTitle' => $this->showTitle,
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
        $this->convertFieldsToTabs($fields)
            ->filter(static function (Tab $tab): bool {
                return $tab->shouldShow();
            })
            ->each(function (Tab $tab): void {
                $this->addFields($tab);
            });


        return $this->data ?? [];
    }

    /**
     * @param $fields
     * @return Collection<TabContract>
     */
    private function convertFieldsToTabs($fields): Collection
    {
        $fieldsCollection = collect(
            \is_callable($fields) ? $fields() : $fields
        );

        return $fieldsCollection->map(function ($fields, $key) {
            return $this->convertToTab($fields, $key);
        })->values();
    }

    /**
     * @param mixed $fields
     * @param string|int $key
     * @return Tab
     */
    private function convertToTab($fields, $key): TabContract
    {
        if ($fields instanceof TabContract) {
            return $fields;
        }

        if ($fields instanceof Panel) {
            return new Tab($fields->name, $fields->data);
        }

        return new Tab($key, $fields);
    }
}
