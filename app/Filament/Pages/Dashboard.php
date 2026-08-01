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

    public function getCards(): array
    {
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $activeBookings = Booking::whereIn('status', ['active', 'confirmed'])->count();
        $totalCustomers = Customer::where('is_active', true)->count();
        $totalVehicles = Vehicle::where(['is_active' => true, 'is_available' => true])->count();

        return [
            \Filament\Widgets\StatsOverviewWidget::make()
                ->columns(4)
                ->stats([
                    \Filament\Widgets\StatsOverviewWidget\Stat::make(
                        'Total Revenue',
                        '€' . number_format($totalRevenue, 2)
                    )
                        ->description('All time')
                        ->descriptionIcon('heroicon-o-arrow-trending-up')
                        ->color('success'),

                    \Filament\Widgets\StatsOverviewWidget\Stat::make(
                        'Active Bookings',
                        $activeBookings
                    )
                        ->description('Currently active')
                        ->descriptionIcon('heroicon-o-calendar')
                        ->color('primary'),

                    \Filament\Widgets\StatsOverviewWidget\Stat::make(
                        'Total Customers',
                        number_format($totalCustomers)
                    )
                        ->description('Active customers')
                        ->descriptionIcon('heroicon-o-user-group')
                        ->color('warning'),

                    \Filament\Widgets\StatsOverviewWidget\Stat::make(
                        'Available Vehicles',
                        number_format($totalVehicles)
                    )
                        ->description('In fleet')
                        ->descriptionIcon('heroicon-o-truck')
                        ->color('info'),
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \Filament\Widgets\ChartWidget::make()
                ->heading('Revenue Overview')
                ->description('Last 12 months')
                ->data(
                    collect(range(1, 12))->map(fn ($month) => [
                        'label' => date('F', mktime(0, 0, 0, $month, 1)),
                        'value' => rand(5000, 25000),
                    ])
                )
                ->type('bar')
                ->colors(['primary', 'success'])
                ->height(300),

            \Filament\Widgets\ChartWidget::make()
                ->heading('Booking Status Distribution')
                ->description('Current bookings')
                ->data(
                    collect(['pending', 'confirmed', 'active', 'completed', 'cancelled'])->map(fn ($status) => [
                        'label' => ucfirst($status),
                        'value' => Booking::where('status', $status)->count(),
                    ])
                )
                ->type('doughnut')
                ->colors(['warning', 'info', 'primary', 'success', 'danger'])
                ->height(300),

            \Filament\Widgets\ChartWidget::make()
                ->heading('Vehicle Type Distribution')
                ->description('Fleet composition')
                ->data(
                    collect(['compact', 'suv', 'sedan', 'van', 'truck'])->map(fn ($type) => [
                        'label' => ucfirst($type),
                        'value' => Vehicle::where('type', $type)->count(),
                    ])
                )
                ->type('pie')
                ->colors(['success', 'primary', 'warning', 'info', 'danger'])
                ->height(300),
        ];
    }
}
