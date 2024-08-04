<?php

namespace App\Security;

use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private readonly AccessTokenRepository $repository) {}

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $accessToken = $this->repository->findOneBy(['token' => $accessToken]);

        if (!$accessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($accessToken->getUser()->getUserIdentifier());
    }
}