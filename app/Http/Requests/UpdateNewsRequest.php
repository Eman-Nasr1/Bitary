<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'summary_ar' => 'required|string',
            'summary_en' => 'nullable|string',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:animal_health,veterinary_medicine,market_trends,other',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'author_name' => 'nullable|string|max:255',
        ];
    }
}
