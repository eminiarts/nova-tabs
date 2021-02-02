<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Nova\Contracts\ListableField;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Http\Requests\NovaRequest;

trait ActionsInTabs
{
    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public function detailFields(NovaRequest $request)
    {
        return $this->availableFields($request)
            ->when($request->viaRelationship(), $this->fieldResolverCallback($request))
//            ->when($this->shouldAddActionsField($request), function ($fields) {
//                return $fields->push($this->actionfield());
//            })
            ->filterForDetail($request, $this->resource)
            ->authorized($request)
            ->each(function ($field) use ($request) {
                if ($field instanceof ListableField || ! $field instanceof Resolvable) {
                    return;
                }

                if ($field->pivot) {
                    $accessor = $this->pivotAccessorFor($request, $request->viaResource);

                    $field->resolveForDisplay($this->{$accessor} ?? new Pivot);
                } else {
                    $field->resolveForDisplay($this->resource);
                }
            });
    }
}
