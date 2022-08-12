<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Closure;
use Eminiarts\Tabs\Contracts\TabContract;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Contracts\ListableField;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceToolElement;
use function is_array;
use function is_callable;

class Tabs extends Panel
{
    /**
     * @var mixed
     */
    public $defaultSearch = false;

    /**
     * @var bool
     */
    public bool $showTitle = false;

    /**
     * @var bool
     */
    public bool $selectFirstTab = true;

    private int $tabsCount = 0;

    public $slug = null;

    private string $preservedName;

    public bool $retainTabPosition = false;

    public $currentColor = null;
    public string $errorColor = 'red';

    /**
     * Create a new panel instance.
     *
     * @param  string  $name
     * @param  (\Closure():array|iterable)|array  $fields
     * @return void
     */
    public function __construct($name, $fields = [])
    {
        $this->name = $name;
        $this->preservedName = $name;
        $this->withComponent('tabs');

        parent::__construct($name, $fields);
    }


    /**
     * Set the tabs slug.
     *
     * @param  string|boolean $slug
     * @return $this
     */
    public function withSlug($slug): Tabs
    {

        $this->slug = is_bool($slug) ? ($slug ? Str::slug($this->preservedName, '_') : null) : $slug;

        return $this;
    }

    /**
     * Set the color for current tabs.
     *
     * @param string $color
     * @return $this
     */
    public function withCurrentColor(string $color): Tabs
    {

        $this->currentColor = $color;

        return $this;
    }

    /**
     * Set the color for tabs with errors.
     *
     * @param  string $color
     * @return $this
     */
    public function withErrorColor(string $color): Tabs
    {

        $this->errorColor = $color;

        return $this;
    }

    /**
     * Remember tab position across detail/edit
     *
     * @param  Boolean $retain
     * @return $this
     */
    public function rememberTabs($retain): Tabs
    {
        $this->retainTabPosition = $retain;
        return $this;
    }

    /**
     * Prepare the given fields.
     *
     * @param  Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields): array
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
            is_callable($fields) ? $fields() : $fields
        );

        return $fieldsCollection->map(function ($fields, $key) {
            return $this->convertToTab($fields, $key);
        })->values();
    }

    /**
     * @param  mixed  $fields
     * @param  string|int  $key
     * @return Tab
     */
    private function convertToTab($fields, $key): TabContract
    {
        if ($fields instanceof TabContract) {
            return $fields;
        }

        $this->tabsCount++;

        if ($fields instanceof Panel) {
            return new Tab($fields->name, $fields->data, $this->tabsCount);
        }

        /**
         * If a field is not nested into an array or a Tab object
         * it acts as a tab in itself
         *
         * @link https://github.com/eminiarts/nova-tabs/issues/141
         */
        if (!is_array($fields)) {
            return new Tab($fields->name, [$fields], $this->tabsCount);
        }

        return new Tab($key, $fields, $this->tabsCount);
    }

    /**
     * Add fields to the Tab.
     *
     * @param  TabContract  $tab
     * @return $this
     */
    public function addFields(TabContract $tab): self
    {
        $this->tabs[] = $tab;

        foreach ($tab->getFields() as $field) {
            if ($field instanceof Panel) {
                $field->assignedPanel = $this;

                $this->addFields(
                    new Tab($field->name, $field->data)
                );
                continue;
            }

            if ($field instanceof MergeValue) {
                if (!isset($field->panel)) {
                    $field->assignedPanel = $this;
                    $field->panel = $this->name;
                }

                $this->addFields(
                    tap(new Tab($tab->getTitle(), $field->data), function (TabContract $newTab) use ($tab): void {
                        if ($tab->getName() !== $tab->getTitle()) {
                            $newTab->name($tab->getName());
                        }
                    })
                );
                continue;
            }

            $field->panel = $this->name;
            $field->assignedPanel = $this;

            $meta = [
                'tab' => $tab->getName(),
                'tabSlug' => $tab->getSlug(),
                'tabPosition' => $tab->getPosition(),
                'tabInfo' => Arr::except($tab->toArray(), ['fields', 'slug'])
            ];

            if ($field instanceof ListableField) {
                $meta += [
                    'listable' => false,
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
     * @param  bool  $value
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
    public function showTitle(bool $show = true): self
    {
        $this->showTitle = $show;

        return $this;
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = array_merge(parent::jsonSerialize(), [
            'defaultSearch' => $this->defaultSearch,
            'showTitle' => $this->showTitle,
            'slug' => $this->slug,
            'retainTabPosition' => $this->retainTabPosition,
            'currentColor' => $this->currentColor,
            'errorColor' => $this->errorColor
        ]);

        return $result;
    }
}
