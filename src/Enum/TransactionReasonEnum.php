<?php

namespace App\Enum;

enum TransactionReasonEnum: string
{
    case STOCK = 'stock';
    case REFUND = 'refund';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
