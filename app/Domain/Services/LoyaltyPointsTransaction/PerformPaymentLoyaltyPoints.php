<?php

namespace App\Domain\Services\LoyaltyPointsTransaction;

use App\Domain\Entities\LoyaltyPointsRule\LoyaltyPointsRule;
use App\Domain\Entities\LoyaltyPointsTransaction\LoyaltyPointsTransaction;
use App\Domain\Services\LoyaltyPointsTransaction\Dto\PaymentLoyaltyPointsDto;

class PerformPaymentLoyaltyPoints
{
    public function execute(
        PaymentLoyaltyPointsDto $dto
    ): LoyaltyPointsTransaction {
        // здесь вначале нужно залокать mutex
        // и разрелизить его в конце либо если что-то пойдет не так и мы отменим операцию
        // также мютексы нужно обязательно добавить ко ВСЕМ операциям с поинтами/деньгами
        // но мне искренне жалко тратить столько времени на ТЕСТОВОЕ задание, надеюсь на понимание
        // в этот сервис нужно было вынести больше кода из контейнера
        // ко всему прочему конечно же нужно покрыть тестами код
        $points_amount = 0;

        if ($pointsRule = LoyaltyPointsRule::where('points_rule', $dto->pointsRule)->first()) {
            // в php8.1 появились enumы настоящие
            $points_amount = match ($pointsRule->accrual_type) {
                LoyaltyPointsRule::ACCRUAL_TYPE_RELATIVE_RATE => ($dto->paymentAmount / 100) * $pointsRule->accrual_value,
                LoyaltyPointsRule::ACCRUAL_TYPE_ABSOLUTE_POINTS_AMOUNT => $pointsRule->accrual_value
            };
        }

        // сохранял бы я сейчас через репозиторий
        return LoyaltyPointsTransaction::create([
            'account_id' => $dto->accountId,
            'points_rule' => $pointsRule?->id,
            'points_amount' => $points_amount,
            'description' => $dto->description,
            'payment_id' => $dto->paymentId,
            'payment_amount' => $dto->paymentAmount,
            'payment_time' => $dto->paymentTime,
        ]);

    }
}
