<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_unit_id',
        'type',
        'amount',
        'date',
        'description',
        'invoice_number',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function vehicleUnit()
    {
        return $this->belongsTo(VehicleUnit::class);
    }
}
