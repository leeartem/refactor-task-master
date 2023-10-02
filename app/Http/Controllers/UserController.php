<?php

namespace App\Http\Controllers;

use App\Domain\Entities\User\User;
use App\Domain\Services\User\AuthorizeUser;
use App\Domain\Services\User\Dto\AuthorizeUserCredentialsDto;
use App\Domain\Services\User\Dto\StoreUserDto;
use App\Domain\Services\User\StoreUser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\AuthenticationUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(
        RegisterRequest $request,
        StoreUser $storeUser
    ) {
        $user = $storeUser->execute(
            new StoreUserDto(
                $request->get('name'),
                $request->get('email'),
                $request->get('password')
            )
        );

        return response()->json(
            [
                'success' => true,
                'user' => $user,
                'token' => $user->createToken('apiToken')->plainTextToken
            ],
            201
        );
    }

    public function login(
        LoginRequest $request,
        AuthorizeUser $authUser
    ) {
        $authenticationUserDto = $authUser->execute(
            new AuthorizeUserCredentialsDto(
                $request->get('email'),
                $request->get('password')
            )
        );

        return response()->json(
            [
                'success' => true,
                'user' => $authenticationUserDto->getUser(),
                'token' => $authenticationUserDto->getUser()->createToken('apiToken')->plainTextToken
            ]
        );
    }

    public function logout(LogoutRequest $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Logged out'
            ]
        );
    }
}
