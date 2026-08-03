<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\PriceRule;
use App\Models\VehicleExtra;
use Carbon\Carbon;

class PricingService implements \App\Interfaces\PricingServiceInterface
{
    public function calculatePrice(Vehicle $vehicle, $startDate, $endDate, array $extras = []): array
    {
        $start = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $end = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);

        $days = max(1, $start->diffInDays($end));
        $hours = $start->diffInHours($end);

        $rules = $this->getApplicableRules($vehicle, $start, $end, $days);

        $basePrice = $this->calculateBasePrice($vehicle, $rules, $start, $end, $days);
        $discount = $this->calculateDiscount($rules, $basePrice);
        
        // Apply volume discount from category
        $volumeDiscountPercent = $this->getVolumeDiscountPercentage($vehicle, $days);
        if ($volumeDiscountPercent > 0) {
            $discount += $basePrice * ($volumeDiscountPercent / 100);
        }
        
        $subtotal = $basePrice - $discount;
        $defaultTax = \App\Models\Tax::where('is_default', true)->where('is_active', true)->first();
        $taxRate = $defaultTax ? (float) $defaultTax->rate : config('extrarent.tax_rate', 21);
        $taxName = $defaultTax ? $defaultTax->name : 'IVA (' . $taxRate . '%)';
        $taxAmount = $subtotal * ($taxRate / 100);
        $extraPrice = $this->calculateExtras($extras, $days);
        $total = $subtotal + $taxAmount + $extraPrice;

        return [
            'base_price' => round($basePrice, 2),
            'discount' => round($discount, 2),
            'subtotal' => round($subtotal, 2),
            'tax_rate' => $taxRate,
            'tax_name' => $taxName,
            'tax_amount' => round($taxAmount, 2),
            'extras_total' => round($extraPrice, 2),
            'total' => round($total, 2),
            'duration_days' => $days,
            'days' => $days,
            'hours' => $hours,
            'rules_applied' => collect($rules)->pluck('rule_type')->toArray(),
        ];
    }

    protected function getApplicableRules(Vehicle $vehicle, Carbon $startDate, Carbon $endDate, int $days): array
    {
        $rules = PriceRule::where(function ($query) use ($vehicle) {
            $query->whereNull('vehicle_id')
                  ->orWhere('vehicle_id', $vehicle->id);
        })
        ->where('is_active', true)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', $startDate->toDateString());
            })->where(function ($q) use ($startDate, $endDate) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $endDate->toDateString());
            });
        })
        ->where(function ($query) use ($days) {
            $query->whereNull('min_days')
                  ->orWhere('min_days', '<=', $days);
        })
        ->where(function ($query) use ($days) {
            $query->whereNull('max_days')
                  ->orWhere('max_days', '>=', $days);
        })
        ->orderBy('priority', 'desc')
        ->orderBy('start_date')
        ->get();

        return $rules->toArray();
    }

    protected function calculateBasePrice(Vehicle $vehicle, array $rules, Carbon $startDate, Carbon $endDate, int $days): float
    {
        // First get the baseline price for the period by checking price periods per day
        $baselineTotal = 0;
        $currentDate = $startDate->copy();
        for ($i = 0; $i < $days; $i++) {
            $baselineDaily = $vehicle->category ? $vehicle->category->getBasePriceForDate($currentDate) : $vehicle->daily_rate;
            $baselineTotal += $baselineDaily;
            $currentDate->addDay();
        }

        if (empty($rules)) {
            return $baselineTotal;
        }

        $total = 0;

        foreach ($rules as $rule) {
            $ruleStart = $rule['start_date'] ? Carbon::parse($rule['start_date']) : $startDate;
            $ruleEnd = $rule['end_date'] ? Carbon::parse($rule['end_date']) : $endDate;

            $overlapStart = max($startDate->timestamp, $ruleStart->timestamp);
            $overlapEnd = min($endDate->timestamp, $ruleEnd->timestamp);

            if ($overlapStart >= $overlapEnd) {
                continue;
            }

            $ruleDays = max(1, floor(($overlapEnd - $overlapStart) / 86400));
            // Get average daily base price for the baseline if rule doesn't have a base_price
            $averageDailyBaseline = $baselineTotal / $days;
            $rulePrice = $this->getRulePrice($rule, $ruleDays, $vehicle, $averageDailyBaseline);
            $total += $rulePrice * $ruleDays;
        }

        return $total > 0 ? $total : $baselineTotal;
    }

    protected function getRulePrice(array $rule, int $days, Vehicle $vehicle, float $defaultBasePrice = null): float
    {
        $basePrice = $rule['base_price'] ?? $defaultBasePrice ?? $vehicle->daily_rate;

        if ($rule['rule_type'] === 'weekly' && $days >= 7) {
            return min($basePrice, ($basePrice / 7) * $days * 0.85);
        }

        if ($rule['rule_type'] === 'monthly' && $days >= 30) {
            return min($basePrice, ($basePrice / 30) * $days * 0.75);
        }

        return $basePrice;
    }

    protected function calculateDiscount(array $rules, float $basePrice): float
    {
        $totalDiscount = 0;

        foreach ($rules as $rule) {
            if (!empty($rule['discount_percentage']) && $rule['discount_percentage'] > 0) {
                $totalDiscount += $basePrice * ($rule['discount_percentage'] / 100);
            }

            if (!empty($rule['discount_amount']) && $rule['discount_amount'] > 0) {
                $totalDiscount += $rule['discount_amount'];
            }
        }

        return min($totalDiscount, $basePrice);
    }

    /**
     * Get the volume discount percentage for a vehicle based on its number of days.
     */
    public function getVolumeDiscountPercentage(Vehicle $vehicle, int $days): float
    {
        return $vehicle->category ? $vehicle->category->getDiscountForDays($days) : 0;
    }

    protected function calculateExtras($extras, int $days): float
    {
        $total = 0;

        foreach ($extras as $extraId) {
            $extra = VehicleExtra::find($extraId);
            if (!$extra) {
                continue;
            }

            if ($extra->calculation_type === 'per_day') {
                $total += ($extra->price ?? 0) * $days;
            } else {
                $total += $extra->price ?? 0;
            }
        }

        return $total;
    }
}
