<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'location_id', 'erp_product_id', 'sku', 'name', 'slug', 'brand', 'model', 'year',
        'license_plate', 'category', 'type', 'body_type', 'fuel_type', 'transmission',
        'engine', 'power_hp', 'seats', 'doors', 'luggage_large', 'luggage_small',
        'automatic_gears', 'color', 'km', 'emission_code', 'energy_type',
        'transmission_type', 'drive_type', 'gearbox', 'image_url', 'description',
        'features', 'is_active', 'is_available', 'show_on_homepage', 'show_on_fleet',
        'show_on_category', 'is_new', 'is_recommended', 'is_featured', 'is_eco',
        'is_electric', 'is_hybrid', 'is_erhverv', 'is_company',
        'is_show_on_homepage', 'is_show_on_fleet', 'is_show_on_category',
        'is_new_listing', 'is_recommended_listing', 'is_featured_listing',
        'is_eco_listing', 'is_electric_listing', 'is_hybrid_listing',
        'is_erhverv_listing', 'is_company_listing',
        'daily_rate', 'weekly_rate', 'monthly_rate', 'security_deposit',
        'erp_sync_status', 'erp_sync_data',
        'category_id',
    ];

    protected $casts = [
        'features' => 'array',
        'erp_sync_data' => 'array',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'show_on_homepage' => 'boolean',
        'show_on_fleet' => 'boolean',
        'show_on_category' => 'boolean',
        'is_new' => 'boolean',
        'is_recommended' => 'boolean',
        'is_featured' => 'boolean',
        'is_eco' => 'boolean',
        'is_electric' => 'boolean',
        'is_hybrid' => 'boolean',
        'is_erhverv' => 'boolean',
        'is_company' => 'boolean',
        'is_show_on_homepage' => 'boolean',
        'is_show_on_fleet' => 'boolean',
        'is_show_on_category' => 'boolean',
        'is_new_listing' => 'boolean',
        'is_recommended_listing' => 'boolean',
        'is_featured_listing' => 'boolean',
        'is_eco_listing' => 'boolean',
        'is_electric_listing' => 'boolean',
        'is_hybrid_listing' => 'boolean',
        'is_erhverv_listing' => 'boolean',
        'is_company_listing' => 'boolean',
        'seats' => 'integer',
        'doors' => 'integer',
        'luggage_large' => 'integer',
        'luggage_small' => 'integer',
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vehicle) {
            if ($vehicle->name && ! $vehicle->slug) {
                $vehicle->slug = Str::slug($vehicle->name);
            }
        });
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function units()
    {
        return $this->hasMany(VehicleUnit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function primaryImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VehicleImage::class)->orderBy('sort_order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(VehicleFeature::class, 'vehicle_feature');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(VehicleTransfer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    public function scopeTransmission($query, $transmission)
    {
        return $query->where('transmission', $transmission);
    }

    public function scopeSeats($query, $seats)
    {
        return $query->where('seats', '>=', $seats);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('daily_rate', [$min, $max]);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%")
                ->orWhere('type', 'like', "%{$term}%");
        });
    }

    public function scopeAvailableBetween($query, $startDate, $endDate)
    {
        return $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
            $q->whereIn('status', ['pending', 'confirmed', 'active'])
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate);
        });
    }

    /**
     * Check if this vehicle is available between two dates.
     */
    public function isAvailableBetween(\DateTimeInterface $startDate, \DateTimeInterface $endDate): bool
    {
        return app(\App\Services\AvailabilityService::class)
            ->isVehicleAvailable($this, $startDate, $endDate);
    }

    public function getPrimaryImageAttribute(): ?VehicleImage
    {
        return $this->images()->orderBy('sort_order')->first();
    }



    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getFormattedPowerAttribute(): string
    {
        return $this->power_hp ? $this->power_hp . ' HP' : '';
    }

    public function getAvailableAttributes(): array
    {
        return [
            'is_active' => $this->is_active,
            'is_available' => $this->is_available,
            'is_eco' => $this->is_eco,
            'is_electric' => $this->is_electric,
            'is_hybrid' => $this->is_hybrid,
            'is_new' => $this->is_new,
            'is_featured' => $this->is_featured,
        ];
    }

    /**
     * Calculate the final price for a given number of days starting from a specific date.
     * Delegates to PricingService for consistent calculations.
     */
    public function calculatePrice(int $days, \DateTimeInterface $startDate = null): float
    {
        $pricingService = app(\App\Services\PricingService::class);
        $start = $startDate ?? now();
        $end = (clone $start)->addDays($days);

        $result = $pricingService->calculatePrice($this, $start, $end);

        return (float) $result['total'];
    }

}
