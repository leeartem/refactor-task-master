<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\LoyaltyPointsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('user/register', [UserController::class, 'register'])->name('register');
Route::post('user/login', [UserController::class, 'login'])->name('login');

//Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user/logout', [UserController::class, 'logout'])->name('user.logout');

    // account management
    Route::post('account/create', [AccountController::class, 'create'])->name('account.create');
    Route::group(['middleware' => ['account.check']], function () {
        Route::post('account/activate/{type}/{$value}', [AccountController::class, 'activate'])->where([
                'type' => '[a-zA-Z]+'
            ])->name('account.activate');
        Route::post('account/deactivate/{type}/{$value}', [AccountController::class, 'deactivate'])->where([
            'type' => '[a-zA-Z]+'
        ])->name('account.deactivate');
        Route::get('account/balance/{type}/{$value}', [AccountController::class, 'balance'])->name('account.balance');
    });

    // loyalty points management
    Route::post('loyaltyPoints/deposit', [LoyaltyPointsController::class, 'deposit'])->name('loyalty-points.deposit');
    Route::post('loyaltyPoints/withdraw', [LoyaltyPointsController::class, 'withdraw'])->name('loyalty-points.withdraw');
    Route::post('loyaltyPoints/cancel', [LoyaltyPointsController::class, 'cancel'])->name('loyalty-points.cancel');
//});





