<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Standard = 'standard';
    case Proforma = 'proforma';
    case CreditNote = 'credit_note';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Factura',
            self::Proforma => 'Proforma',
            self::CreditNote => 'Nota de crédito',
        };
    }
}
