<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Livewire::component('vehicle-search', \App\Http\Livewire\VehicleSearch::class);
        Livewire::component('booking-form', \App\Http\Livewire\BookingForm::class);
        Livewire::component('vehicle-calendar', \App\Http\Livewire\VehicleCalendar::class);

        // View composers
        \View::composer('layouts.app', \App\View\Composers\MenuComposer::class);
    }
}
