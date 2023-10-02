<?php

namespace App\Domain\Services\User\Dto;

class StoreUserDto
{
    public function __construct(
        private string $name, // C PHP 8.1 появилась удобная штука public readonly.
        // В dto ее в основном использую, чтобы геттеры все эти не писать
        private string $email,
        private string $password,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
