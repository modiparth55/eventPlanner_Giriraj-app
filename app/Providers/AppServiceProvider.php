<?php

namespace App\Providers;

use App\Models\Events;
use App\Observers\EventRecurrenceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Events::observe(EventRecurrenceObserver::class);
    }
}
