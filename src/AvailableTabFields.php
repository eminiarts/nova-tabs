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
}
