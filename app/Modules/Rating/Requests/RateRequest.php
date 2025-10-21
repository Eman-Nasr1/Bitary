<?php

namespace App\Modules\Rating\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends FormRequest
{
    public function rules(): array
    {
         return [
            'rateable_id' => ['required', 'integer'],
            'rateable_type' => ['required', 'string'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
