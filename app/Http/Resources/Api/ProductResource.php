<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'name_ar'     => $this->name_ar,
            'name_en'    => $this->name_en,
            'image'      => $this->image,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'is_active'  => $this->is_active,
            'price'      => $this->price,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
        ];    }
}
