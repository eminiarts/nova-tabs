<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Traits;

use Eminiarts\Tabs\Tabs;
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
        [$defaultFields, $fieldsWithPanels] = $fields->each(function ($field) {
            if ($field instanceof BehavesAsPanel && !$field->assignedPanel instanceof Tabs) {
                $field->asPanel();
            }
        })->partition(function ($field) {
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

        if ($panels->where('component', 'tabs')->isEmpty()) {
            return $this->panelsWithDefaultLabel(
                $panels,
                $defaultFields->values(),
                $label
            );
        }

        [$relationshipUnderTabs, $panels] = $panels->partition(function ($panel) {
            return $panel->component === 'relationship-panel' && $panel->meta['fields'][0]->assignedPanel instanceof Tabs;
        });

        $panels->transform(function($panel, $key) use ($relationshipUnderTabs) {

            if ($panel->component === 'tabs') {

                foreach ($relationshipUnderTabs as $rel) {
                    if ($panel->meta['fields'][0]->assignedPanel === $rel->meta['fields'][0]->assignedPanel) {
                        $panel->meta['fields'][] = $rel->meta['fields'][0];
                    }
                }

                $panel->name = $panel->meta['fields'][0]->panel;
                $panel->showTitle = $panel->meta['fields'][0]->assignedPanel->showTitle;
                $panel->showToolbar = $panel->meta['fields'][0]->assignedPanel->showToolbar;
                $panel->slug = $panel->meta['fields'][0]->assignedPanel->slug;
                $panel->currentColor = $panel->meta['fields'][0]->assignedPanel->currentColor;
                $panel->errorColor = $panel->meta['fields'][0]->assignedPanel->errorColor;
                $panel->retainTabPosition = $panel->meta['fields'][0]->assignedPanel->retainTabPosition;
            }

            return $panel;
        });

        return $this->panelsWithDefaultLabel(
            $panels,
            $defaultFields->values(),
            $label
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
            ->when($panels->where('name', $label)->isEmpty(), function ($panels) use ($label, $fields) {
                return $fields->isNotEmpty()
                    ? $panels->prepend(Panel::make($label, $fields)->withMeta(['fields' => $fields]))
                    : $panels;
            })
            ->tap(function ($panels) use ($label): void {

                /**
                 * There can be no panels
                 * Preventing ->component or ->withToolbar() on null error
                 */
                if(!$panels->first()){
                    return;
                }

                /**
                 * Default to ->withToolbar() if the first panel is not a Tabs
                 * Otherwise, we show the tabs component with the settings configured within it
                 * This check is necessary in case you ARE using Tabs component, but only for relation as a 2nd or 3rd instance
                 */
                $panels->first()->component !== 'tabs' ? $panels->first()->withToolbar() : $panels->where('component', 'tabs')->first();
            });
    }
}
