<?php

namespace App\DTO;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class WalletCreateRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'currency cannot be empty.')]
        #[Assert\Callback([self::class, 'validateCurrency'])]
        public readonly CurrencyEnum $currency,
    ) {
    }

    public static function validateCurrency(CurrencyEnum $currency, ExecutionContextInterface $context): void
    {
        if (!CurrencyEnum::tryFrom($currency->value)) {
            $context->buildViolation('Invalid currency value.')
                ->atPath('currency')
                ->addViolation();
        }
    }
}