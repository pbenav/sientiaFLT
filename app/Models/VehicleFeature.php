<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleFeature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'is_required'];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_feature');
    }
}
