<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function getUserByCredentials(string $email, string $password): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return null;
        }

        if ($this->userPasswordHasher->isPasswordValid($user, $password) === false) {
            return null;
        }

        return $user;
    }
}