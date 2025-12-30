<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'family_name' => $this->family_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_verified' => $this->is_verified,
            'is_provider' => $this->is_provider ?? false,
            'is_active' => $this->is_active ?? true,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
