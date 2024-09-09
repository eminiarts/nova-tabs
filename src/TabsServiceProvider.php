<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class TabsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event): void {
            Nova::script('tabs', __DIR__.'/../dist/js/field.js');
            Nova::style('tabs', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Routes
     */
    protected function routes()
    {
        // if ($this->app->routesAreCached()) {
        //     return;
        // }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/eminiarts/nova-tabs')
            ->group(__DIR__.'/../routes/api.php');
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
