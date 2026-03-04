<?php

namespace App\Modules\City\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateCityRequest extends FormRequest
{
    public function rules(): array
    {
        $cityId = $this->route('city') ?? $this->route('id');
        
        return [
            'name_ar' => 'required|string|max:255|unique:cities,name_ar,' . $cityId,
            'name_en' => 'required|string|max:255|unique:cities,name_en,' . $cityId,
        ];
    }
}

