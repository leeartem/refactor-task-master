<?php

namespace App\Domain\Services\User\Dto;

use App\Domain\Entities\User\User;

class AuthenticationUserDto
{
    public function __construct(
        private User $user,
        private string $token,
    ) {
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
