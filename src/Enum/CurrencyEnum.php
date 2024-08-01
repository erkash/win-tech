<?php

namespace App\Enum;

enum CurrencyEnum: string
{
    case USD = 'usd';
    case RUB = 'rub';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
