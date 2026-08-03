<?php

namespace App\Enums;

enum BookingSource: string
{
    case Website = 'website';
    case Phone = 'phone';
    case WalkIn = 'walk_in';
    case Erp = 'erp';
    case ICal = 'ical';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Web',
            self::Phone => 'Teléfono',
            self::WalkIn => 'Presencial',
            self::Erp => 'ERP',
            self::ICal => 'iCal',
        };
    }
}
