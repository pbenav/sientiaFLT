<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'symbol', 'decimal_places', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
        'decimal_places' => 'integer',
    ];
}
