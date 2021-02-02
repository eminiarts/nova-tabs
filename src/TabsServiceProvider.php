<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class TabsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event): void {
            Nova::script('tabs', __DIR__ . '/../dist/js/field.js');
            Nova::style('tabs', __DIR__ . '/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }
}
