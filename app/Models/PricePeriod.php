<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PricePeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'base_price',
        'active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(VehicleCategory::class, 'category_price_periods')
            ->withTimestamps();
    }

    /**
     * Check if this period is active on a given date.
     */
    public function isActiveOn(\DateTimeInterface $date): bool
    {
        return $this->active
            && $date->format('Y-m-d') >= $this->start_date
            && $date->format('Y-m-d') <= $this->end_date;
    }

    /**
     * Get formatted display string.
     */
    public function getDisplayAttribute(): string
    {
        $start = date('d/m/Y', strtotime($this->start_date));
        $end = date('d/m/Y', strtotime($this->end_date));
        return sprintf('%s → %s (%s€/día)', $start, $end, number_format((float) $this->base_price, 2, ',', '.'));
    }
}
