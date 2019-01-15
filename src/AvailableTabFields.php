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
                        // Not sure if needed, will have to see. $item['fields'] should always be an array.
                        // if (!$item['fields']->showOnDetail || !$item['fields']->authorize(request())) {
                        //     $item['fields'] = null;
                        // }
                    }

                    return $item;
                });
            }

            return $item;
        });
    }
}
