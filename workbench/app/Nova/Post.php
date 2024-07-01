<?php

declare(strict_types=1);

namespace Workbench\App\Nova;

use BBSLab\Tabs\Tabs;
use BBSLab\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Post extends Resource
{
    use HasTabs;

    public static $model = \Workbench\App\Models\Post::class;

    public static $title = 'slug';

    public static $search = [
        'id', 'title', 'slug',
    ];

    public function fields(NovaRequest $request): array
    {
        $tab = match (true) {
            $request->isCreateOrAttachRequest() => Panel::defaultNameForCreate($this),
            $request->isUpdateOrUpdateAttachedRequest() => Panel::defaultNameForUpdate($this),
            default => Panel::defaultNameForDetail($this),
        };

        return [

            Tabs::make((string) $tab, [
                'Details' => [
                    ID::make()->sortable(),

                    Text::make('Title')
                        ->sortable()
                        ->rules('required', 'max:255'),

                    Slug::make('Slug')
                        ->from('title')
                        ->sortable()
                        ->rules('required', 'max:255'),
                ],
                'Content' => [
                    Textarea::make('Content')
                        ->sortable()
                        ->rules('required', 'max:255'),
                ],
            ])
                ->showTitle()
                ->withToolbar()
                ->defaultSearch()
                ->help('Help text'),
        ];
    }
}
