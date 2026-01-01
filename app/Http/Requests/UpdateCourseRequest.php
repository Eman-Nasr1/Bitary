<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'sometimes|required|in:vets,students,breeders',
            'level' => 'sometimes|required|in:beginner,intermediate,advanced',
            'language' => 'sometimes|required|in:ar,en,mixed',
            'overview_ar' => 'nullable|string',
            'overview_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'intro_video_url' => 'nullable|url',
            'intro_video_iframe' => 'nullable|string',
            'duration_weeks' => 'nullable|integer|min:1',
            'hours_per_week' => 'nullable|integer|min:1',
            'days_per_week' => 'nullable|integer|min:1|max:7',
            'certificate_available' => 'boolean',
            'certificate_type' => 'nullable|in:digital,physical',
            'is_free' => 'boolean',
            'payment_method' => 'sometimes|required|in:online,whatsapp',
            'status' => 'sometimes|required|in:draft,published,archived',
            'specializations' => 'nullable|array',
            'specializations.*' => 'exists:specializations,id',
            'instructors' => 'nullable|array',
            'instructors.*' => 'exists:instructors,id',
        ];

        // If is_free is false, price is required
        if ($this->has('is_free') && !$this->input('is_free')) {
            $rules['price'] = 'required|numeric|min:0';
            $rules['currency'] = 'required|string|max:10';
            $rules['discount_percent'] = 'nullable|numeric|min:0|max:100';
        }

        // If payment_method is whatsapp, whatsapp_join_link is required
        if ($this->input('payment_method') === 'whatsapp') {
            $rules['whatsapp_join_link'] = 'required|url';
        }

        // If certificate_available is true, certificate_type is required
        if ($this->input('certificate_available')) {
            $rules['certificate_type'] = 'required|in:digital,physical';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // Set price, currency, discount_percent to null if is_free is true
        if ($this->has('is_free') && $this->input('is_free')) {
            $this->merge([
                'price' => null,
                'currency' => null,
                'discount_percent' => null,
            ]);
        }
    }
}
