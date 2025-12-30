<?php

namespace App\Modules\ProviderRequest\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin auth checked via middleware
    }

    public function rules(): array
    {
        return [
            'admin_note' => 'nullable|string|max:1000',
        ];
    }
}
