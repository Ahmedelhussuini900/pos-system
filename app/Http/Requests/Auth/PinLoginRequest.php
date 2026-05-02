<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class PinLoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pin_code' => ['required', 'digits:6'],
        ];
    }
}
