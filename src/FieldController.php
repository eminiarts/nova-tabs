<?php
namespace Eminiarts\Tabs;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class FieldController extends Controller
{
    /**
     * Retrieve the given field for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(NovaRequest $request)
    {
        return response()->json(
            $request->newResource()
                ->availableFields($request)
                ->findFieldByAttribute($request->field, function () use ($request) {
                    // Go through all the Fields and see if the requested Field is in Tabs
                    return $request->newResource()
                        ->availableFields($request)
                        ->pluck('tabs')
                        ->reject(function ($name) {
                            return empty($name);
                        })
                        ->flatten(1)
                        ->pluck('field')
                        ->findFieldByAttribute($request->field, function () {
                            abort(404);
                        });
                })
        );
    }
}
