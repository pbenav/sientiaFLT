<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolumeDiscount extends Model
{
    protected $fillable = [
        'vehicle_category_id',
        'min_days',
        'max_days',
        'discount_percent',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function appliesTo(int $days): bool
    {
        if ($this->max_days === null) {
            return $days >= $this->min_days;
        }
        return $days >= $this->min_days && $days <= $this->max_days;
    }
}
