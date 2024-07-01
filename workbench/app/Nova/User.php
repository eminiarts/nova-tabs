<?php

declare(strict_types=1);

namespace Workbench\App\Nova;

use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class User extends Resource
{
    public static $model = \Workbench\App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'email',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Panel::make('Test', [
                Text::make('Name')
                    ->sortable()
                    ->rules('required', 'max:255'),

                Text::make('Email')
                    ->sortable()
                    ->rules('required', 'email', 'max:254')
                    ->creationRules('unique:users,email')
                    ->updateRules('unique:users,email,{{resourceId}}'),

                Password::make('Password')
                    ->onlyOnForms()
                    ->creationRules('required', Rules\Password::defaults())
                    ->updateRules('nullable', Rules\Password::defaults()),
            ])
                ->help('Help text'),
        ];
    }
}
