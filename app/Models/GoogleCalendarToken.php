<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleCalendarToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name', 'access_token', 'refresh_token', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
