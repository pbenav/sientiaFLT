<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Payment;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Database\Query\Builder;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Dashboard');
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\NewBookingNotificationWidget::class,
            \App\Filament\Widgets\DashboardStatsWidget::class,
            \App\Filament\Widgets\RevenueChartWidget::class,
            \App\Filament\Widgets\BookingStatusChartWidget::class,
            \App\Filament\Widgets\VehicleTypeChartWidget::class,
            \App\Filament\Widgets\POSStatsWidget::class,
            \App\Filament\Widgets\RecentBookingsWidget::class,
        ];
    }
}
