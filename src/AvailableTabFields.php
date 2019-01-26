<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Http\Requests\NovaRequest;

trait AvailableTabFields
{
    /**
     * Get all available Tab Fields and merge them with the parent Fields.
     */
    public function availableFields(NovaRequest $request)
    {
        $fields = parent::availableFields($request);

        return $fields->merge($fields->whereInstanceOf(Tabs::class)->map(function ($tab, $key) {
            return $tab->availableFields();
        })->flatten());
    }

    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public function detailFields(NovaRequest $request)
    {
        $fields = parent::detailFields($request);

        return $fields->map(function ($item, $key) {
            if ($item instanceof Tabs) {
                $item->tabs = collect($item->tabs)->map(function ($item, $key) {

                    if (is_array($item['fields'])) {
                        $item['fields'] = collect($item['fields'])->reject(function ($field) {
                            return !$field->showOnTabs || !$field->authorize(request());
                        });
                    } else {
                        if (!$item['fields']->showOnTabs || !$item['fields']->authorize(request())) {
                            return;
                        }
                    }

                    return $item;
                })->reject(function ($tab) {
                    return empty($tab);
                });
            }

            return $item;
        });
    }

    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public function updateFields(NovaRequest $request)
    {
        //dd('hier');
        //return $this->resolveFields($request);

        return $this->removeNonUpdateFields($this->resolveFields($request));

        return $fields->reject(function ($field) {
            return $field instanceof ListableField ||
            $field instanceof ResourceToolElement ||
            ($field instanceof ID && $field->attribute === $this->resource->getKeyName()) ||
            $field->attribute === 'ComputedField' ||
            !$field->showOnUpdate;
        });
    }
}
