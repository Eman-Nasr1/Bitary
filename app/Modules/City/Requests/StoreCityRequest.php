<?php

namespace App\Modules\City\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255|unique:cities,name_ar',
            'name_en' => 'required|string|max:255|unique:cities,name_en',
        ];
    }
}


