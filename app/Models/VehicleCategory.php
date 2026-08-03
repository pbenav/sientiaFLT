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
        'icon',
        'sort_order',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'base_price' => 'decimal:2',
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
        return $this->hasMany(CategoryVolumeDiscount::class)->orderBy('min_days');
    }

    public function getBasePriceForDate(\DateTimeInterface $date): ?float
    {
        $periodPrice = $this->pricePeriods()
            ->where('active', true)
            ->where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'))
            ->value('base_price');

        return $periodPrice ?? $this->base_price;
    }

    public function getCurrentBasePrice(): ?float
    {
        return $this->getBasePriceForDate(now());
    }

    public function getDiscountForDays(int $days): float
    {
        $discount = $this->volumeDiscounts()
            ->where('min_days', '<=', $days)
            ->where(function ($query) use ($days) {
                $query->whereNull('max_days')
                      ->orWhere('max_days', '>=', $days);
            })
            ->orderByDesc('min_days')
            ->first();

        return $discount ? (float) $discount->discount_percent : 0;
    }
}
