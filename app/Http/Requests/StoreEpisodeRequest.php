<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEpisodeRequest extends FormRequest
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
        $rules = [
            'podcast_id' => 'required|exists:podcasts,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'episode_type' => 'required|in:video,audio',
            'status' => 'required|in:draft,published,hidden',
            'published_at' => 'nullable|date',
            'spotify_url' => 'nullable|url',
            'apple_podcasts_url' => 'nullable|url',
        ];

        // Conditional validation based on episode_type
        if ($this->input('episode_type') === 'video') {
            $rules['youtube_url'] = 'required|url';
            $rules['audio_file'] = 'nullable';
        } elseif ($this->input('episode_type') === 'audio') {
            $rules['youtube_url'] = 'nullable|url';
            // Either audio_file OR (spotify_url OR apple_podcasts_url) must be provided
            $rules['audio_file'] = [
                'nullable',
                'file',
                'mimes:mp3,wav',
                'max:51200', // 50MB max
                Rule::requiredIf(function () {
                    return empty($this->input('spotify_url')) && empty($this->input('apple_podcasts_url'));
                }),
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'youtube_url.required' => 'YouTube URL is required for video episodes.',
            'audio_file.required' => 'Either audio file or external link (Spotify/Apple Podcasts) is required for audio episodes.',
        ];
    }
}
