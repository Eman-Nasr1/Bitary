<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        $rules = [
            'specialization_id' => 'sometimes|required|exists:job_specializations,id',
            'city_id' => 'nullable|exists:cities,id',
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'job_type' => 'nullable|in:full_time,part_time',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'responsibilities_ar' => 'nullable|string',
            'responsibilities_en' => 'nullable|string',
            'qualifications_ar' => 'nullable|string',
            'qualifications_en' => 'nullable|string',
            'apply_method' => 'sometimes|required|in:in_app,whatsapp,email,external_link',
            'status' => 'sometimes|in:draft,pending,published,rejected,archived',
            'rejection_reason' => 'nullable|string',
        ];

        if ($this->input('apply_method') == 'whatsapp') {
            $rules['whatsapp_number'] = 'required|string|max:20';
        } elseif ($this->input('apply_method') == 'email') {
            $rules['email_address'] = 'required|email|max:255';
        } elseif ($this->input('apply_method') == 'external_link') {
            $rules['external_link'] = 'required|url|max:500';
        }

        return $rules;
    }
}
