<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Payment;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.reports';

    public function getBookingsByStatus(): array
    {
        return Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function getTopVehicles(): array
    {
        return Vehicle::withCount('bookings')
            ->where('is_active', true)
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'brand', 'model', 'daily_rate'])
            ->toArray();
    }

    public function getTopCustomers(): array
    {
        return Customer::withCount('bookings')
            ->where('is_active', true)
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'company_name'])
            ->toArray();
    }

    public function getMonthlyRevenue(): array
    {
        return Payment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->pluck('total', 'month')
            ->toArray();
    }

    public function getOccupancyRate(): float
    {
        $totalVehicles = Vehicle::count();
        if ($totalVehicles === 0) {
            return 0;
        }

        $activeBookings = Booking::where('status', 'active')->get();
        $totalDays = 0;
        $bookedDays = 0;

        foreach ($activeBookings as $booking) {
            $totalDays += $totalVehicles;
            $bookedDays++;
        }

        return $totalDays > 0 ? round(($bookedDays / $totalDays) * 100, 2) : 0;
    }
}
