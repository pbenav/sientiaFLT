<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class VehicleCategory extends Model
{
    protected $table = 'vehicle_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (! $category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'category_id');
    }

    public function pricePeriods(): BelongsToMany
    {
        return $this->belongsToMany(PricePeriod::class, 'category_price_periods')
            ->withTimestamps();
    }

    public function volumeDiscounts(): HasMany
    {
        return $this->hasMany(CategoryVolumeDiscount::class);
    }

    /**
     * Get the base price for a specific date.
     * Returns the base_price of the active price period that covers the given date.
     */
    public function getBasePriceForDate(\DateTimeInterface $date): ?float
    {
        return $this->pricePeriods()
            ->where(function ($query) use ($date) {
                $query->where('start_date', '<=', $date->format('Y-m-d'))
                    ->where('end_date', '>=', $date->format('Y-m-d'));
            })
            ->value('base_price');
    }

    /**
     * Get the applicable discount percentage for a given number of days.
     */
    public function getDiscountForDays(int $days): float
    {
        $discount = $this->volumeDiscounts()
            ->where(function ($query) use ($days) {
                $query->where('min_days', '<=', $days)
                    ->where(function ($q) use ($days) {
                        $q->whereNull('max_days')
                          ->orWhere('max_days', '>=', $days);
                    });
            })
            ->orderBy('min_days', 'desc')
            ->first();

        return $discount ? (float) $discount->discount_percent : 0;
    }
}
