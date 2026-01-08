<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePodcastRequest extends FormRequest
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
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'podcast_type' => 'required|in:video,audio,both',
            'status' => 'required|in:draft,published,archived',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:podcast_categories,id',
            'youtube_channel_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'apple_podcasts_url' => 'nullable|url',
        ];
    }
}
