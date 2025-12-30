<?php

namespace App\Modules\ProviderRequest\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // User must be authenticated via middleware
    }

    public function rules(): array
    {
        return [
            'entity_name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'degree' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'google_maps_link' => 'nullable|url|max:500',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'license_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ];
    }
}
