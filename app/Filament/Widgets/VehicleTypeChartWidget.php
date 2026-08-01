<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class VehicleTypeChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    public function getHeading(): string
    {
        return __('Vehicle Type Distribution');
    }

    public function getDescription(): string
    {
        return __('Fleet composition');
    }

    protected function getData(): array
    {
        $types = ['compact', 'suv', 'sedan', 'van', 'truck', 'scooter', 'coupe', 'hatchback'];
        
        return [
            'datasets' => [
                [
                    'label' => __('Vehicles'),
                    'data' => collect($types)->map(fn ($t) => Vehicle::where('type', $t)->count())->toArray(),
                    'backgroundColor' => ['#10b981', '#3b82f6', '#f59e0b', '#0ea5e9', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'],
                ],
            ],
            'labels' => collect($types)->map(fn ($t) => __(Str::ucfirst($t)))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
