<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'service_type', 'name', 'unit_price', 'total_price',
        'quantity', 'calculation_type', 'daily_rate', 'per_km_rate',
        'included_km', 'extra_km_rate', 'description',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'extra_km_rate' => 'decimal:2',
        'included_km' => 'integer',
        'quantity' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
