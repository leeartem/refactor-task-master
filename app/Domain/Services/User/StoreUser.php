<?php

namespace App\Domain\Services\User;

use App\Domain\Entities\User\IUserRepository;
use App\Domain\Entities\User\User;
use App\Domain\Services\User\Dto\StoreUserDto;
use Illuminate\Support\Facades\Hash;

class StoreUser
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(
        StoreUserDto $dto
    ): User {
        $user = new User();
        $user->name = $dto->getName();
        $user->email = $dto->getEmail();
        $user->password = Hash::make($dto->getPassword());

        return $this->userRepository->save($user);
    }
}
