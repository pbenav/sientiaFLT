<?php

namespace App\Enums;

enum VehicleType: string
{
    case Compact = 'compact';
    case SUV = 'suv';
    case Sedan = 'sedan';
    case Van = 'van';
    case Truck = 'truck';
    case Scooter = 'scooter';
    case Coupe = 'coupe';
    case Hatchback = 'hatchback';
    case PickUp = 'pickup';
    case Minivan = 'minivan';
    case Luxury = 'luxury';
    case Camper = 'camper';
    case Motorhome = 'motorhome';

    public function label(): string
    {
        return match ($this) {
            self::Compact => 'Compacto',
            self::SUV => 'SUV',
            self::Sedan => 'Sedán',
            self::Van => 'Furgoneta',
            self::Truck => 'Camión',
            self::Scooter => 'Scooter',
            self::Coupe => 'Coupé',
            self::Hatchback => 'Hatchback',
            self::PickUp => 'Pick-up',
            self::Minivan => 'Minivan',
            self::Luxury => 'Lujo',
            self::Camper => 'Autocaravana',
            self::Motorhome => 'Furgoneta camper',
        };
    }
}
