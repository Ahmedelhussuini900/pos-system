<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Requests\ApiRequest;

class UpdateProductRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
