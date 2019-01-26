<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Http\Requests\NovaRequest;

trait TabsOnUpdate
{
    /**
     * Resolve the update fields.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function updateFields(NovaRequest $request)
    {
        return collect(
            [
                'Tabs' => [
                    'component' => 'tabs',
                    'fields'    => $this->removeNonUpdateFields($this->resolveFields($request)),
                ],
            ]
        );
    }
}
