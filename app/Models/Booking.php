<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'erp_documento_id', 'booking_number', 'vehicle_id',
        'vehicle_unit_id', 'location_id', 'start_date', 'end_date', 'start_location',
        'end_location', 'return_location', 'is_round_trip', 'driver_age',
        'has_additional_driver', 'status', 'payment_status',
        'subtotal', 'tax_amount', 'total_amount', 'amount_paid',
        'amount_due', 'currency_code', 'deposit_amount',
        'special_requests', 'customer_notes', 'internal_notes', 'notes',
        'booking_source', 'utm_source', 'utm_medium', 'utm_campaign',
        'referral_code', 'is_confirmed', 'is_paid', 'is_active',
        'erp_sync_status', 'erp_sync_data', 'tpv_ticket_id', 'alquiler_id',
        'pos_session_id', 'user_id', 'payment_method_id', 'fecha_entrega',
        'fecha_devolucion', 'discount_amount',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_round_trip' => 'boolean',
        'has_additional_driver' => 'boolean',
        'is_confirmed' => 'boolean',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
        'amount_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'erp_sync_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = \App\Services\BookingNumberGenerator::generate();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(VehicleUnit::class, 'vehicle_unit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function tpvTicket(): BelongsTo
    {
        return $this->belongsTo(TicketTPV::class, 'tpv_ticket_id');
    }

    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class, 'alquiler_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getDurationHoursAttribute(): int
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'active' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            'no_show' => 'red',
            default => 'gray',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->end_date->isPast();
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture() && in_array($this->status, ['pending', 'confirmed']);
    }
}
