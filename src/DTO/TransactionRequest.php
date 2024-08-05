<?php

namespace App\DTO;

use App\Enum\CurrencyEnum;
use App\Enum\TransactionReasonEnum;
use App\Enum\TransactionTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Currency cannot be empty.')]
        #[Assert\Choice(callback: [CurrencyEnum::class, 'getValues'])]
        public readonly string $currency,
        #[Assert\NotBlank(message: 'Type cannot be empty.')]
        #[Assert\Choice(callback: [TransactionTypeEnum::class, 'getValues'])]
        public readonly string $type,
        #[Assert\NotBlank(message: 'Reason cannot be empty.')]
        #[Assert\Choice(callback: [TransactionReasonEnum::class, 'getValues'])]
        public readonly string $reason,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $amount,
    ) {
    }

}