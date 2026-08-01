<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ICalFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url', 'type', 'is_active', 'sync_interval', 'last_sync_data',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sync_interval' => 'integer',
        'last_sync_data' => 'array',
    ];
}
