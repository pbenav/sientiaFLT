<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'rule_type', 'name', 'start_date', 'end_date',
        'min_days', 'max_days', 'min_km', 'max_km', 'base_price',
        'discount_percentage', 'discount_amount', 'price_per_km',
        'extra_km_price', 'priority', 'is_active', 'conditions',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'price_per_km' => 'decimal:2',
        'extra_km_price' => 'decimal:2',
        'min_days' => 'integer',
        'max_days' => 'integer',
        'min_km' => 'integer',
        'max_km' => 'integer',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
