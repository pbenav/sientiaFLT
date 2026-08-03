<?php

namespace App\Filament\Actions;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;

class UpdateBookingTotals
{
    public static function make(): \Closure
    {
        return function (Get $get, Set $set) {
            $vehicleId = $get('vehicle_id');
            $start = $get('start_date');
            $end = $get('end_date');
            $discount = (float) $get('discount_amount');

            if ($vehicleId && $start && $end) {
                $vehicle = \App\Models\Vehicle::find($vehicleId);
                if ($vehicle) {
                    $pricingService = app(\App\Services\PricingService::class);
                    $pricing = $pricingService->calculatePrice($vehicle, $start, $end);

                    $basePrice = $pricing['base_price'];
                    $autoDiscount = $pricing['discount'];

                    // Use manual discount if provided, else use auto calculated discount
                    $finalDiscount = $discount > 0 ? $discount : $autoDiscount;
                    if ($discount == 0 && $autoDiscount > 0) {
                        $set('discount_amount', round($autoDiscount, 2));
                    }

                    $subtotal = $basePrice - $finalDiscount;
                    $taxRate = $pricing['tax_rate'];
                    $taxAmount = $subtotal * ($taxRate / 100);
                    $total = $subtotal + $taxAmount;

                    $set('subtotal', round($subtotal, 2));
                    $set('tax_amount', round($taxAmount, 2));
                    $set('total_amount', round($total, 2));

                    if (blank($get('deposit_amount'))) {
                        $set('deposit_amount', round((float) $vehicle->security_deposit, 2));
                    }
                }
            }
        };
    }
}
