<?php

namespace App\Domain\Entities\LoyaltyAccount;

use App\Domain\Entities\LoyaltyPointsTransaction\LoyaltyPointsTransaction;
use App\Mail\AccountActivated;
use App\Mail\AccountDeactivated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyAccount extends Model
{
    protected $table = 'loyalty_account';

    protected $fillable = [
        'phone',
        'card',
        'email',
        'email_notification',
        'phone_notification',
        'active',
    ];

    public function getBalance(): float
    {
        return LoyaltyPointsTransaction::where('canceled', '=', 0)->where('account_id', '=', $this->id)->sum('points_amount');
    }

    public function notify(): void
    {
        // этот метод мне не нравится
        if ($this->email_notification) {
            if (!empty($this->email)) {
                if ($this->active) {
                    Mail::to($this)->send(new AccountActivated($this->getBalance()));
                } else {
                    Mail::to($this)->send(new AccountDeactivated());
                }
            }

            if (!empty($this->phone )) {
                // instead SMS component
                Log::info('Account: phone: ' . $this->phone . ' ' . ($this->active ? 'Activated' : 'Deactivated'));
            }
        }
    }

    public function setActive(bool $activate = true): void
    {
        if ($this->active !== $activate) {
            $this->active = $activate;
            $this->save();
        }
    }
}
