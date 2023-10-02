<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Entities\User\IUserRepository;
use App\Domain\Entities\User\User;

class UserRepository extends AbstractRepository implements IUserRepository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function save(User $user): User
    {
        $user->save();
        return $user;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->getNewQuery()
            ->where('email', $email)
            ->first();
    }

}
