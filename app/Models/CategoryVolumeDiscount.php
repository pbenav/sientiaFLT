<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryVolumeDiscount extends Model
{
    protected $table = 'category_volume_discounts';

    protected $fillable = [
        'vehicle_category_id',
        'min_days',
        'max_days',
        'discount_percent',
        'sort_order',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }
}
