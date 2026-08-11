<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'forma_pagos';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'activo',
        'descripcion',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getTipoBadgeAttribute(): string
    {
        return match ($this->tipo) {
            'contado' => 'green',
            'transferencia' => 'blue',
            'tarjeta' => 'purple',
            'pagare' => 'yellow',
            'recibo_bancario' => 'indigo',
            'efectivo' => 'green',
            default => 'gray',
        };
    }
}
