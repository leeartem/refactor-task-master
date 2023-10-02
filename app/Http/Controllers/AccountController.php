<?php

namespace App\Http\Controllers;

use App\Events\LoyaltyAccountStatusChangedEvent;
use App\Http\Requests\AccountRequest;
use App\Models\LoyaltyAccount;
use Illuminate\Http\Request;

// кроме user контроллера я не буду уж выносить логику, и все разбивать, так много времени на это уходит
class AccountController extends Controller
{
    public function create(Request $request)
    {
        // тут все аналогично юзерам
        // делаем кастомный реквест с валидацией
        // создаем репозиторий, выносим сторинг в сервис
        // передав значения через ДТО
        return LoyaltyAccount::create($request->all());
    }

    public function activate(string $type, int $id, AccountRequest $request)
    {
        /** @var LoyaltyAccount $account */
        if (!$account = LoyaltyAccount::where($type, $id)->first()) {
            return response()->json(['message' => 'Account is not found'], 400);
        }

        // в идеале конечно для активации/деактивации свой мини сервис нужно создать
        $account->setActive();
        event(
            new LoyaltyAccountStatusChangedEvent($account->id, $account->active)
        );

        return response()->json(['success' => true]);
    }

    public function deactivate(string $type, int $id, AccountRequest $request)
    {
        /** @var LoyaltyAccount $account */
        if (!$account = LoyaltyAccount::where($type, $id)->first()) {
            return response()->json(['message' => 'Account is not found'], 400);
        }

        $account->setActive(false);
        event(
            new LoyaltyAccountStatusChangedEvent($account->id, $account->active)
        );

        return response()->json(['success' => true]);
    }

    public function balance(string $type, int $id, AccountRequest $request)
    {
        /** @var LoyaltyAccount $account */
        if (!$account = LoyaltyAccount::where($type, $id)->first()) {
            return response()->json(['message' => 'Account is not found'], 400);
        }

        return response()->json(['balance' => $account->getBalance()], 400);
    }
}
