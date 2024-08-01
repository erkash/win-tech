<?php

namespace App\Service\ExchangeRate;

use App\Entity\ExchangeRate;
use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ExchangeRateService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ExchangeRateRepository $exchangeRateRepository
    ) {
    }

    public function create(CurrencyEnum $from, CurrencyEnum $to, float $rate): ExchangeRate
    {
        $rate *= 100;
        $rate = (int)$rate;

        $exchangeRate = new ExchangeRate();
        $exchangeRate
            ->setFromCurrency($from)
            ->setToCurrency($to)
            ->setRate($rate);

        $this->em->persist($exchangeRate);
        $this->em->flush();

        return $exchangeRate;
    }

    public function updateRate(ExchangeRate $exchangeRate, float $rate): ExchangeRate
    {
        $rate *= 100;
        $exchangeRate->setRate((int)$rate);

        $this->em->flush();

        return $exchangeRate;
    }

    public function convert(CurrencyEnum $from, CurrencyEnum $to, int $amount): int
    {
        $exchangeRate = $this->exchangeRateRepository->findOneBy([
            'fromCurrency' => $from,
            'toCurrency' => $to
        ]);

        if (!$exchangeRate) {
            throw new \InvalidArgumentException("Exchange rate from {$from->value} to {$to->value} not found.");
        }

        $rate = $exchangeRate->getRate() / 100;
        $convertedAmount = $amount * $rate;

        return (int)round($convertedAmount);
    }
}
