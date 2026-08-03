<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'is_active',
        'is_default',
        'country_code',
        'description',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Ensure only one tax can be default at a time.
     */
    protected static function booted()
    {
        static::saving(function ($tax) {
            if ($tax->is_default) {
                static::where('id', '!=', $tax->id)->update(['is_default' => false]);
            }
        });
    }
}
