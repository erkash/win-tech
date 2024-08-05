<?php

namespace App\Service\User;

use App\DTO\UserRequest;
use App\Entity\User;
use App\Exception\UserExistException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class ApiUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function getUserByCredentials(string $username, string $password): ?User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        if ($this->passwordHasher->isPasswordValid($user, $password) === false) {
            throw new InvalidPasswordException('Invalid password');
        }

        return $user;
    }

    /**
     * @throws UserExistException
     */
    public function create(UserRequest $registerRequest): User
    {
        try {
            $existingUser = $this->userRepository->findOneBy(['username' => $registerRequest->username]);
            if ($existingUser) {
                throw new UserExistException('User already exists.');
            }

            $user = new User();
            $user->setUsername($registerRequest->username);
            $password = $registerRequest->password;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

        } catch (UserExistException $e) {
            throw new UserExistException($e->getMessage());
        }

        return $user;
    }
}