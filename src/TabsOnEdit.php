<?php
namespace Eminiarts\Tabs;

use Laravel\Nova\Http\Requests\NovaRequest;

trait TabsOnEdit
{
    /**
     * @param NovaRequest $request
     * @param $model
     */
    public static function fillForUpdate(NovaRequest $request, $model)
    {
        return static::fillFields(
            $request, $model,
            (new static($model))->parentUpdateFields($request)
        );
    }

    /**
     * @param NovaRequest $request
     */
    public function parentUpdateFields(NovaRequest $request)
    {
        return parent::updateFields($request);
    }

    /**
     * Get the validation rules for a resource update request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public static function rulesForUpdate(NovaRequest $request)
    {
        return static::formatRules($request, (new static(static::newModel()))
                ->parentUpdateFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getUpdateRules($request);
                })->all());
    }

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
