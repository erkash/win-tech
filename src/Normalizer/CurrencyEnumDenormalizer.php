<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Enum\CurrencyEnum;

class CurrencyEnumDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (!CurrencyEnum::tryFrom($data)) {
            throw new InvalidArgumentException('Invalid currency value.');
        }

        return CurrencyEnum::from($data);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === CurrencyEnum::class;
    }
}