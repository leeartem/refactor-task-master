<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => 'required|email|',
            'password' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
