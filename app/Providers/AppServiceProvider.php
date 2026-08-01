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

        // Global Filament label translation
        \Filament\Forms\Components\Field::configureUsing(function (\Filament\Forms\Components\Field $field): void {
            $field->translateLabel();
        });

        \Filament\Tables\Columns\Column::configureUsing(function (\Filament\Tables\Columns\Column $column): void {
            $column->translateLabel();
        });

        \Filament\Tables\Columns\TextColumn::configureUsing(function (\Filament\Tables\Columns\TextColumn $column): void {
            if (in_array($column->getName(), ['status', 'payment_status', 'type', 'visibility', 'role'])) {
                $column->formatStateUsing(fn ($state) => is_string($state) ? __($state) : $state);
            }
        });

        \Filament\Tables\Filters\BaseFilter::configureUsing(function (\Filament\Tables\Filters\BaseFilter $filter): void {
            $filter->translateLabel();
        });

        \Filament\Actions\Action::configureUsing(function (\Filament\Actions\Action $action): void {
            $action->translateLabel();
        });
    }
}
