<?php

namespace App\Http\Controllers;

use App\Domain\Entities\LoyaltyAccount\LoyaltyAccount;
use App\Domain\Entities\LoyaltyPointsTransaction\LoyaltyPointsTransaction;
use App\Domain\Services\LoyaltyPointsTransaction\Dto\PaymentLoyaltyPointsDto;
use App\Domain\Services\LoyaltyPointsTransaction\PerformPaymentLoyaltyPoints;
use App\Http\Requests\LoyaltyPointsRequest;
use App\Mail\LoyaltyPointsReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyPointsController extends Controller
{
    public function deposit(
        LoyaltyPointsRequest $request,
        PerformPaymentLoyaltyPoints $performPaymentLoyaltyPoints
    ) {
        Log::info(
            'Deposit transaction',
            [
                'input' => $request->validated()
            ]
        );

        if (!$account = LoyaltyAccount::where($request->get('account_type'), $request->get('account_id'))->first()) {
            Log::info('Account is not found');
            return response()->json(['message' => 'Account is not found'], 400);
        }

        if (!$account->active) {
            Log::info('Account is not active');
            return response()->json(['message' => 'Account is not active'], 400);
        }

        $transaction = $performPaymentLoyaltyPoints->execute(
            new PaymentLoyaltyPointsDto(
                accountId: $account->id,
                pointsRule:  $request->get('loyalty_points_rule'),
                description: $request->get('description'),
                paymentId: $request->get('payment_id'),
                paymentAmount: $request->get('payment_amount'),
                paymentTime: $request->get('payment_time')
            )
        );

        Log::info(
            'Transaction created',
            [
                // чтобы можно было легко найти лог по айди транзакции
                'transaction_id' => $transaction->id,
                'transaction' => $transaction
            ]
        );

        // этот код обязательно нужно переписать,
        // но честно, тестовое очень большое
        // на человеческий рефакторинг нужно потратить очень много часов
        if ($account->phone_notification) {
            if ($account->email != '') {
                Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
            }
            if ($account->phone != '') {
                // instead SMS component
                Log::info('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
            }
        }

        return $transaction;

    }

    public function cancel()
    {
        $data = $_POST;

        $reason = $data['cancellation_reason'];

        if ($reason == '') {
            return response()->json(['message' => 'Cancellation reason is not specified'], 400);
        }

        if ($transaction = LoyaltyPointsTransaction::where('id', '=', $data['transaction_id'])->where('canceled', '=', 0)->first()) {
            $transaction->canceled = time();
            $transaction->cancellation_reason = $reason;
            $transaction->save();
        } else {
            return response()->json(['message' => 'Transaction is not found'], 400);
        }
    }

    public function withdraw()
    {
        $data = $_POST;

        Log::info('Withdraw loyalty points transaction input: ' . print_r($data, true));

        $type = $data['account_type'];
        $id = $data['account_id'];
        if (($type == 'phone' || $type == 'card' || $type == 'email') && $id != '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                if ($account->active) {
                    if ($data['points_amount'] <= 0) {
                        Log::info('Wrong loyalty points amount: ' . $data['points_amount']);
                        return response()->json(['message' => 'Wrong loyalty points amount'], 400);
                    }
                    if ($account->getBalance() < $data['points_amount']) {
                        Log::info('Insufficient funds: ' . $data['points_amount']);
                        return response()->json(['message' => 'Insufficient funds'], 400);
                    }

                    $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $data['points_amount'], $data['description']);
                    Log::info($transaction);
                    return $transaction;
                } else {
                    Log::info('Account is not active: ' . $type . ' ' . $id);
                    return response()->json(['message' => 'Account is not active'], 400);
                }
            } else {
                Log::info('Account is not found:' . $type . ' ' . $id);
                return response()->json(['message' => 'Account is not found'], 400);
            }
        } else {
            Log::info('Wrong account parameters');
            throw new \InvalidArgumentException('Wrong account parameters');
        }
    }
}
