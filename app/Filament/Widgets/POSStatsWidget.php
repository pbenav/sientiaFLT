<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class POSStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $ticketsToday = Booking::whereDate('created_at', $today)->where('booking_source', 'pos')->count();
        $totalToday = Booking::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $alquileresActivos = Booking::where('status', 'active')->count();
        $alquileresPendientes = Booking::where('payment_status', 'unpaid')->count();

        $ticketsPendientes = Booking::where('booking_source', 'pos')->where('status', 'pending')->count();

        return [
            Stat::make('Tickets Hoy (POS)', $ticketsToday)
                ->description('Ventas de caja del día')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make('Total Cobrado Hoy', number_format($totalToday, 2) . '€')
                ->description('Ingresos confirmados')
                ->descriptionIcon('heroicon-o-currency-euro')
                ->color('success'),
            Stat::make('Alquileres Activos', $alquileresActivos)
                ->description('Vehículos en calle')
                ->descriptionIcon('heroicon-o-truck')
                ->color('warning'),
            Stat::make('Pagos Pendientes', $alquileresPendientes)
                ->description('Reservas por cobrar')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),
            Stat::make('Tickets en Espera', $ticketsPendientes)
                ->description('Tickets POS sin cerrar')
                ->descriptionIcon('heroicon-o-document')
                ->color('warning'),
        ];
    }
}
