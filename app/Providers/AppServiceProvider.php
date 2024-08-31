<?php

namespace App\Providers;

use App\Actions\DefaultProcessProductAction;
use App\Contracts\ProcessProductAction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProcessProductAction::class,
            DefaultProcessProductAction::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
