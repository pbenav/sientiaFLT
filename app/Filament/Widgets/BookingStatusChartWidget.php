<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class BookingStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): string
    {
        return __('Booking Status Distribution');
    }

    public function getDescription(): string
    {
        return __('Current bookings');
    }

    protected function getData(): array
    {
        $statuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        
        return [
            'datasets' => [
                [
                    'label' => __('Bookings'),
                    'data' => collect($statuses)->map(fn ($s) => Booking::where('status', $s)->count())->toArray(),
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#6b7280', '#ef4444'],
                ],
            ],
            'labels' => collect($statuses)->map(fn ($s) => __(Str::ucfirst($s)))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
