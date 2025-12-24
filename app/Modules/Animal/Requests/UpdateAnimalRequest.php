<?php

namespace App\Modules\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAnimalRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'animal_type_id' => 'nullable|integer|exists:animal_types,id',

        ];
    }
}
