<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Partial = 'partial';
    case Paid = 'paid';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Impago',
            self::Partial => 'Parcial',
            self::Paid => 'Pagado',
            self::Refunded => 'Reembolsado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Unpaid => 'red',
            self::Partial => 'yellow',
            self::Paid => 'green',
            self::Refunded => 'gray',
        };
    }
}
