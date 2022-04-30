<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Traits;

use ConsultLive\Tabs\Tabs;
use Illuminate\Support\Collection;
use Laravel\Nova\Contracts\BehavesAsPanel;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

trait HasTabs
{
    /**
     * Resolve available panels from fields.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Fields\FieldCollection<int, \Laravel\Nova\Fields\Field>  $fields
     * @param  string  $label
     * @return Collection
     */
    protected function resolvePanelsFromFields(NovaRequest $request, FieldCollection $fields, $label)
    {
        [$defaultFields, $fieldsWithPanels] = $fields->partition(function ($field) {
            return ! isset($field->panel) || blank($field->panel);
        });

        $panels = $fieldsWithPanels->groupBy(function ($field) {
            return $field->panel;
        })->transform(function ($fields, $name) {
            if ($fields[0]->assignedPanel instanceof Tabs) {
                return Tabs::mutate($name, $fields);
            }

            return Panel::mutate($name, $fields);
        })->toBase();

        if ($panels->where('component', 'tabs')->count() === 0) {
            return parent::resolvePanelsFromFields($request, $fields, $label);
        }

        [$relationshipUnderTabs, $panels] = $panels->partition(function ($panel) {
            return $panel->component === 'relationship-panel' && $panel->meta['fields'][0]->assignedPanel instanceof Tabs;
        });

        $panels->transform(function($panel, $key) use($relationshipUnderTabs) {

            if ($panel->component === 'tabs') {

                foreach ($relationshipUnderTabs as $rel) {
                    if ($panel->meta['fields'][0]->assignedPanel === $rel->meta['fields'][0]->assignedPanel) {
                        $panel->meta['fields'][] = $rel->meta['fields'][0];
                    }
                }

                return $panel->withMeta([
                    'showToolbar' => $panel->meta['fields'][0]->assignedPanel->showToolbar,
                    'showTitle' => $panel->meta['fields'][0]->assignedPanel->showTitle,
                ]);
            }

            return $panel;
        });

        $panels->first()->name = $panels->first()->meta['fields'][0]->panel;

        return $this->panelsWithDefaultLabel(
            $panels, $defaultFields->values(), $label
        );
    }

    /**
     * Return the panels for this request with the default label.
     *
     * @param  \Illuminate\Support\Collection<int, \Laravel\Nova\Panel>  $panels
     * @param  \Laravel\Nova\Fields\FieldCollection<int, \Laravel\Nova\Fields\Field>  $fields
     * @param  string  $label
     * @return \Illuminate\Support\Collection<int, \Laravel\Nova\Panel>
     */
    protected function panelsWithDefaultLabel(Collection $panels, FieldCollection $fields, $label)
    {
        return $panels->values()
            ->when($panels->where('component', 'tabs')->isEmpty(), function ($panels) use ($label, $fields) {
                return $panels->prepend(
                    Tabs::make($label, $fields)->withMeta(['fields' => $fields])
                );
            })
            ->tap(function ($panels) use ($label): void {
                $panels->where('component', 'tabs')->first();
            });
    }
}
