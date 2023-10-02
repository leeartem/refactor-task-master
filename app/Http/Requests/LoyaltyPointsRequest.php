<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_type'        => 'required|string|in:phone,card,email',
            'account_id'          => 'required|string',
            'loyalty_points_rule' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
