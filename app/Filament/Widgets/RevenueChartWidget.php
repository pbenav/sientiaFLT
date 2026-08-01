<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('Revenue Overview');
    }

    public function getDescription(): string
    {
        return __('Last 12 months');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => __('Revenue'),
                    'data' => collect(range(1, 12))->map(fn () => rand(5000, 25000))->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => collect(range(1, 12))->map(fn ($month) => __(Carbon::create()->month($month)->translatedFormat('F')))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
