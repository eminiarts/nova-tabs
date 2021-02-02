<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Eminiarts\Tabs\FieldCollection as TabsFieldCollection;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

trait TabsOnEdit
{
    /**
     * Resolve the creation fields.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function creationFields(NovaRequest $request)
    {
        return $this->assignTabPanels($this->removeNonCreationFields($request, $this->resolveFields($request)));
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Model     $model
     * @return array
     */
    public static function fill(NovaRequest $request, $model)
    {
        return static::fillFields(
            $request,
            $model,
            (new static($model))->creationFieldsWithoutReadonly($request)
        );
    }

    /**
     * @param NovaRequest $request
     * @param $model
     */
    public static function fillForUpdate(NovaRequest $request, $model)
    {
        return static::fillFields(
            $request,
            $model,
            (new static($model))->updateFieldsWithoutReadonly($request)
        );
    }

    /**
     * Return the creation fields excluding any readonly ones.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function creationFieldsWithoutReadonly(NovaRequest $request)
    {
        return $this->parentCreationFields($request)
                    ->reject(function ($field) use ($request) {
                        return $field->isReadonly($request);
                    });
    }

    /**
     * Return the update fields excluding any readonly ones.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function updateFieldsWithoutReadonly(NovaRequest $request)
    {
        return $this->parentUpdateFields($request)
                    ->reject(function ($field) use ($request) {
                        return $field->isReadonly($request);
                    });
    }

    /**
     * @param NovaRequest $request
     */
    public function parentCreationFields(NovaRequest $request)
    {
        return parent::creationFields($request);
    }

    /**
     * @param NovaRequest $request
     */
    public function parentUpdateFields(NovaRequest $request)
    {
        return parent::updateFields($request);
    }

    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public static function rulesForCreation(NovaRequest $request)
    {
        return static::formatRules($request, (new static(static::newModel()))
                ->parentCreationFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getCreationRules($request);
                })->all());
    }

    /**
     * Get the validation rules for a resource update request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public static function rulesForUpdate(NovaRequest $request, $resource = null)
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
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    public function updateFields(NovaRequest $request)
    {
        return $this->assignTabPanels($this->removeNonUpdateFields($request, $this->resolveFields($request)));
    }

    /**
     * Assign the fields with the given panels to their parent panel.
     *
     * @param  string  $label
     * @param  \Laravel\Nova\Fields\FieldCollection  $fields
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    protected function assignToPanels($label, FieldCollection $fields)
    {
        return $fields->map(function ($field) use ($label) {
            if (!is_array($field) && !$field->panel) {

                $field->panel = $label;
            }

            return $field;
        });
    }

    /**
     * @param NovaRequest $request
     * @return Resource
     */
    private function resolveResource(NovaRequest $request)
    {
        if ($this instanceof Resource) {
            return $this;
        }

        return $request->newResource();
    }

    /**
     * Assigns fields to proper tab components
     * @param  \Laravel\Nova\Fields\FieldCollection $Fields
     * @return \Laravel\Nova\Fields\FieldCollection
     */
    private function assignTabPanels(FieldCollection $fields)
    {
        $tabPanels = [];

        $nonTabFields = $fields->filter(function ($field, $key) use (&$tabPanels) {
            $isTabField = isset($field->meta['tab']);
            if ($isTabField) {
                if (! isset($tabPanels[$field->panel])) {
                    $newPanel = [
                        'component' => 'tabs',
                        'panel' => $field->panel,
                        'fields' => []
                    ];
                    $tabPanels[$field->panel] = $newPanel;
                }
                $tabPanels[$field->panel]['fields'][] = $field;
            }
            return !$isTabField;
        });

        return $nonTabFields->concat($tabPanels);
    }
}
