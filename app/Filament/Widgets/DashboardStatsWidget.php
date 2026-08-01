<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $activeBookings = Booking::whereIn('status', ['active', 'confirmed'])->count();
        $totalCustomers = Customer::where('is_active', true)->count();
        $totalVehicles = Vehicle::where(['is_active' => true, 'is_available' => true])->count();

        return [
            Stat::make(__('Total Revenue'), '€' . number_format($totalRevenue, 2))
                ->description(__('All time'))
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make(__('Active Bookings'), $activeBookings)
                ->description(__('Currently active'))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make(__('Total Customers'), number_format($totalCustomers))
                ->description(__('Active customers'))
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),
            Stat::make(__('Available Vehicles'), number_format($totalVehicles))
                ->description(__('In fleet'))
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
        ];
    }
}
