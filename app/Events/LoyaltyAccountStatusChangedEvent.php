<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class LoyaltyAccountStatusChangedEvent
{
    use Dispatchable;

    // дальше уже ловим ивент и уведомляем юзера
    public function __construct(
        int $accountId,
        string $status
    ) {
    }
}
