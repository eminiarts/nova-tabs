<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Traits;

use Eminiarts\Tabs\Tabs;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\FieldCollection;

trait HasTabs
{
    /**
     * Return the panels for this request with the default label.
     *
     * @param  Collection  $panels
     * @param  FieldCollection  $fields
     * @param  string  $label
     * @return Collection
     */
    protected function panelsWithDefaultLabel(Collection $panels, FieldCollection $fields, $label)
    {

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
            }

            return $panel;
        });

        return $panels->values()
            ->when($panels->where('component', 'tabs')->isEmpty(), function ($panels) use ($label, $fields) {
                return $panels->prepend(
                    Tabs::make($label, $fields)->withMeta(['fields' => $fields])->withToolbar()
                );
            })->tap(function ($panels) use ($label): void {
                $panels->where('component', 'tabs')->first()->withToolbar();
            });
    }
}
