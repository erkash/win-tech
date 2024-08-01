<?php

namespace App\Enum;

enum TransactionTypeEnum: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
