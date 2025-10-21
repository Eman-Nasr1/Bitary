<?php

namespace App\Modules\User\Requests;

use Illuminate\Validation\Rules\Enum;
use App\Modules\User\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|min:1|exists:users,id',
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,$this->userId",
            'phone' => "required|string|unique:users,phone,$this->userId",
            'isActive'=> 'nullable|boolean',
            'isVerified'=> 'nullable|boolean',
        ];
    }

    public function  prepareForValidation()
    {
        $this->merge([
            'userId' => (int) $this->route('userId'),
        ]);
    }
}
