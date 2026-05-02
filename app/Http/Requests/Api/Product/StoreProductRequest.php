<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Requests\ApiRequest;

class StoreProductRequest extends ApiRequest
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
