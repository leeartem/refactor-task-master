<?php

namespace App\Domain\Services\LoyaltyPointsTransaction\Dto;

class PaymentLoyaltyPointsDto
{
    // просто чтобы не писать сейчас кучу геттеров сделал их паблик
    // опять же в новой PHP я бы просто сделал их public readonly
    public function __construct(
        public $accountId,
        public $pointsRule,
        public $description,
        public $paymentId,
        public $paymentAmount,
        public $paymentTime
    ) {
    }
}
