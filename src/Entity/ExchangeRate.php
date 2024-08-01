<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private CurrencyEnum $fromCurrency;

    #[ORM\Column(length: 3)]
    private CurrencyEnum $toCurrency;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $rate = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromCurrency(): ?CurrencyEnum
    {
        return $this->fromCurrency;
    }

    public function setFromCurrency(CurrencyEnum $fromCurrency): static
    {
        $this->fromCurrency = $fromCurrency;

        return $this;
    }

    public function getToCurrency(): ?CurrencyEnum
    {
        return $this->toCurrency;
    }

    public function setToCurrency(CurrencyEnum $toCurrency): static
    {
        $this->toCurrency = $toCurrency;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
