<?php

namespace App\Service\Transaction;

use App\DTO\TransactionRequest;
use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionReasonEnum;
use App\Enum\TransactionTypeEnum;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function create(TransactionRequest $transactionRequest, Wallet $wallet): Transaction
    {
        $transaction = new Transaction();
        $currencyEnum = CurrencyEnum::from($transactionRequest->currency);
        $typeEnum = TransactionTypeEnum::from($transactionRequest->type);
        $reasonEnum = TransactionReasonEnum::from($transactionRequest->reason);

        $transaction
            ->setCurrency($currencyEnum)
            ->setType($typeEnum)
            ->setReason($reasonEnum)
            ->setAmount($transactionRequest->amount)
            ->setWallet($wallet);

        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }
}