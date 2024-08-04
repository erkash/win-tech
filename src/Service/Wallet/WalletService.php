<?php

namespace App\Service\Wallet;

use App\DTO\WalletCreateRequest;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Wallet;
use App\Enum\TransactionReasonEnum;
use App\Service\ExchangeRate\ExchangeRateService;
use Doctrine\ORM\EntityManagerInterface;

final class WalletService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ExchangeRateService $rateService
    ) {
    }

    public function createWallet(
        WalletCreateRequest $walletCreateRequest,
        User $user
    ): Wallet {
        $wallet = new Wallet();
        $wallet
            ->setCurrency($walletCreateRequest->currency)
            ->setUser($user);

        $this->em->persist($wallet);
        $this->em->flush();

        return $wallet;
    }

    public function updateBalance(Transaction $transaction, Wallet $wallet): void
    {
        $amount = $transaction->getAmount();

        if ($transaction->getCurrency() !== $wallet->getCurrency()) {
            $amount = $this->rateService->convert(
                $transaction->getCurrency(),
                $wallet->getCurrency(),
                $amount
            );
        }

        $balance = $wallet->getBalance();

        $newBalance = match ($transaction->getReason()) {
            TransactionReasonEnum::STOCK => $balance + $amount,
            TransactionReasonEnum::REFUND => $balance - $amount
        };

        $wallet->setBalance($newBalance);

        $this->em->flush();
    }
}
