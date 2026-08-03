<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingNumberGenerator
{
    public static function generate(): string
    {
        $date = now()->format('Ymd');

        $number = DB::transaction(function () use ($date) {
            $count = DB::table('bookings')
                ->whereRaw('DATE(created_at) = ?', [now()->toDateString()])
                ->count();

            return str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        });

        return 'BK-' . $date . '-' . $number;
    }

    public static function generateInvoice(): string
    {
        $year = now()->year;

        $number = DB::transaction(function () use ($year) {
            $count = DB::table('invoices')
                ->whereRaw('YEAR(created_at) = ?', [$year])
                ->count();

            return str_pad($count + 1, 5, '0', STR_PAD_LEFT);
        });

        return 'INV-' . $year . '-' . $number;
    }

    public static function generateLocator(): string
    {
        return 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    }
}
