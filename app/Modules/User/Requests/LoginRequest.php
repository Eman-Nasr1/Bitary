<?php

namespace App\Modules\User\Requests;

use Illuminate\Validation\Rules\Enum;
use App\Modules\User\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email', 
           'password' => 'required|string', 
        ];
    }

   
}
