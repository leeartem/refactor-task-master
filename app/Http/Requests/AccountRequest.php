<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class AccountRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'phone' => 'required|string',
            'card'  => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
