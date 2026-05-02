<?php

namespace App\Http\Requests\Api\Category;

use App\Http\Requests\ApiRequest;

class UpdateCategoryRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
     return [
          'name_ar'       => ['sometimes', 'string', 'max:255'],
          'name_en'       => ['sometimes', 'string', 'max:255'],
          'image'      => ['nullable', 'image', 'max:2048'],
          'is_active'  => ['nullable', 'boolean'],
          'sort_order' => ['nullable', 'integer', 'min:0'],
      ];

    }
}
