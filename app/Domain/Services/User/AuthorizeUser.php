<?php

namespace App\Domain\Services\User;

use App\Domain\Entities\User\IUserRepository;
use App\Domain\Entities\User\User;
use App\Domain\Services\User\Dto\AuthenticationUserDto;
use App\Domain\Services\User\Dto\AuthorizeUserCredentialsDto;
use App\Domain\Services\User\Dto\StoreUserDto;
use App\Exceptions\User\BadCredentialsException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Resources\User\AuthenticationUserResource;
use Illuminate\Support\Facades\Hash;
use Psr\Log\LoggerInterface;

class AuthorizeUser
{
    public function __construct(
        private IUserRepository $userRepository,
        private LoggerInterface $logger
    ) {
    }

    public function execute(
        AuthorizeUserCredentialsDto $dto
    ): AuthenticationUserDto {
        $user = $this->userRepository->findUserByEmail($dto->getEmail());
        if (null === $user) {
            // конкретно здесь можно и не логировать конечно, но пусть будет
            $this->logger->error(
                'User was not found while auth',
                [
                    'email' => $dto->getEmail()
                ]
            );
            throw new UserNotFoundException('User not found');
        }

        if (Hash::check($dto->getPassword(), $user->password)) {
            $token = $user->createToken('apiToken')->plainTextToken;
        } else {
            throw new BadCredentialsException('Bad credentials');
        }

        return new AuthenticationUserDto(
            $user,
            $token
        );
    }
}
