<?php

namespace App\Modules\Seller\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSellerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'description_ar' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'availability' => 'nullable|string|max:50', 
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

