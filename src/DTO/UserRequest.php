<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Username cannot be empty.')]
        #[Assert\Length(
            min: 3,
            minMessage: 'Username must be at least {{ limit }} characters long.'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9_]+$/',
            message: 'Username can only contain letters, numbers, and underscores.'
        )]
        public readonly string $username,

        #[Assert\NotBlank(message: 'Password cannot be empty.')]
        #[Assert\Length(
            min: 6,
            minMessage: 'Password must be at least {{ limit }} characters long.'
        )]
        #[Assert\Regex(
            pattern: '/^(?=.*[A-Z])(?=.*\d).+$/',
            message: 'Password must contain at least one uppercase letter and one number.'
        )]
        public readonly string $password
    ) {
    }
}