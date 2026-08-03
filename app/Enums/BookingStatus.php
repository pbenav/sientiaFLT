<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Confirmed => 'Confirmada',
            self::Active => 'Activa',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
            self::NoShow => 'No se presentó',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'blue',
            self::Active => 'green',
            self::Completed => 'gray',
            self::Cancelled => 'red',
            self::NoShow => 'red',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Confirmed, self::Active]);
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled, self::NoShow]);
    }
}
