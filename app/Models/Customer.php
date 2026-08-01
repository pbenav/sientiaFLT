<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'erp_tercio_id', 'email', 'phone', 'first_name', 'last_name',
        'company_name', 'nif_cif', 'address', 'city', 'province',
        'postal_code', 'country', 'notes', 'is_active', 'is_company',
        'locale', 'currency_code', 'erp_sync_status', 'erp_sync_data',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_company' => 'boolean',
        'erp_sync_data' => 'array',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getSearchableNameAttribute(): string
    {
        return $this->full_name;
    }
}
