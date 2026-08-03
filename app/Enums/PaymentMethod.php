<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Stripe = 'stripe';
    case Bizum = 'bizum';
    case Paypal = 'paypal';
    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';

    public function label(): string
    {
        return match ($this) {
            self::Stripe => 'Stripe',
            self::Bizum => 'Bizum',
            self::Paypal => 'PayPal',
            self::Cash => 'Efectivo',
            self::BankTransfer => 'Transferencia',
        };
    }
}
