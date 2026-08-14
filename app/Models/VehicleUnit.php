<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VehicleUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'license_plate',
        'vin',
        'color',
        'purchase_date',
        'purchase_price',
        'current_km',
        'extras',
        'status',
        'notes',
    ];

    protected $casts = [
        'extras' => 'array',
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_unit_id');
    }

    public function expenses()
    {
        return $this->hasMany(VehicleExpense::class);
    }

    protected function totalRevenue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->bookings()->whereIn('status', ['completed', 'active', 'confirmed'])->sum('total_amount'),
        );
    }

    protected function maintenanceCost(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->expenses()->sum('amount'),
        );
    }

    public function getTotalDaysRentedAttribute()
    {
        return $this->bookings()->whereIn('status', ['completed', 'active', 'confirmed'])->get()->sum(function($booking) {
            return max(1, \Carbon\Carbon::parse($booking->start_date)->startOfDay()->diffInDays(\Carbon\Carbon::parse($booking->end_date)->startOfDay()));
        });
    }

    public function getAmortizationProgressAttribute()
    {
        if (!$this->purchase_price || $this->purchase_price <= 0) return 0;
        $progress = ($this->total_revenue / $this->purchase_price) * 100;
        return min(100, $progress);
    }

    public function getRoiAttribute()
    {
        if (!$this->purchase_price) return 0;
        return $this->total_revenue - $this->maintenance_cost - $this->purchase_price;
    }

    public function getUtilizationRateAttribute()
    {
        if (!$this->purchase_date) return 0;
        $daysSincePurchase = max(1, $this->purchase_date->diffInDays(now()));
        $rate = ($this->total_days_rented / $daysSincePurchase) * 100;
        return min(100, $rate);
    }
}
