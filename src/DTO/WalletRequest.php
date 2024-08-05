<?php

namespace App\DTO;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;

class WalletRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'currency cannot be empty.')]
        #[Assert\Choice(callback: [CurrencyEnum::class, 'getValues'])]
        public readonly string $currency,
    ) {
    }
}