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
          'category_id'    => ['required', 'integer', 'exists:categories,id'],
          'name_ar'       => ['required', 'string', 'max:255'],
          'name_en'       => ['required', 'string', 'max:255'],
          'description_ar' => ['nullable', 'string'],
          'description_en' => ['nullable', 'string'],
          'price'          => ['required', 'numeric', 'min:0'],
          'image'      => ['nullable', 'image', 'max:2048'],
          'is_active'  => ['nullable', 'boolean'],
          'sort_order' => ['nullable', 'integer', 'min:0'],
      ];
    }
}
