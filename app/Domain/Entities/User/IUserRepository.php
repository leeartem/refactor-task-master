<?php

namespace App\Domain\Entities\User;

interface IUserRepository
{
    public function save(User $user): User;

    public function findUserByEmail(string $email): ?User;
}
