<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Http\Requests\NovaRequest;

trait ActionsInTabs
{
    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public function detailFields(NovaRequest $request)
    {
        return $this->resolveFields($request)->reject(function ($field) use ($request) {
            return !$field->showOnDetail || !$field->authorize($request);
        })->when(in_array(Actionable::class, class_uses_recursive(static::newModel())), function ($fields) {
            //return $fields->push(MorphMany::make(__('Actions'), 'actions', ActionResource::class));
        })->each(function ($field) use ($request) {

            if ($field instanceof Resolvable && !$field->pivot) {
                $field->resolveForDisplay($this->resource);

            }
            if ($field instanceof Resolvable && $field->pivot) {
                $accessor = $this->pivotAccessorFor($request, $request->viaResource);

                $field->resolveForDisplay($this->{$accessor} ?? new Pivot());
            }
        });
    }
}
