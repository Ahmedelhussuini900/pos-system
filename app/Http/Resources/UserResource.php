<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'role'      => [
                'name'         => $this->role->name,
                'display_name' => $this->role->display_name,
            ],
            'abilities' => $this->role->abilities->pluck('name'),
        ];
    }
}
