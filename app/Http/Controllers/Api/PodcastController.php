<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * List all published podcasts with episodes, categories, and instructor
     */
    public function index(Request $request)
    {
        $query = Podcast::with([
            'episodes.instructor',
            'categories'
        ])->where('status', 'published');

        // Search by title (Arabic or English)
        if ($request->has('search') || $request->has('title')) {
            $search = $request->get('search') ?? $request->get('title');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category_id
        if ($request->has('category_id')) {
            $categoryId = $request->get('category_id');
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('podcast_categories.id', $categoryId);
            });
        }

        // Pagination
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        
        $total = $query->count();
        $podcasts = $query->orderBy('id', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function($podcast) {
                return [
                    'id' => $podcast->id,
                    'title_ar' => $podcast->title_ar,
                    'title_en' => $podcast->title_en,
                    'description_ar' => $podcast->description_ar,
                    'description_en' => $podcast->description_en,
                    'cover_image' => $podcast->cover_image_url,
                    'podcast_type' => $podcast->podcast_type,
                    'status' => $podcast->status,
                    'youtube_channel_url' => $podcast->youtube_channel_url,
                    'spotify_url' => $podcast->spotify_url,
                    'apple_podcasts_url' => $podcast->apple_podcasts_url,
                    'categories' => $podcast->categories->map(function($category) {
                        return [
                            'id' => $category->id,
                            'name_ar' => $category->name_ar,
                            'name_en' => $category->name_en,
                        ];
                    }),
                    'episodes' => $podcast->episodes()->where('status', 'published')->get()->map(function($episode) {
                        return [
                            'id' => $episode->id,
                            'title_ar' => $episode->title_ar,
                            'title_en' => $episode->title_en,
                            'description_ar' => $episode->description_ar,
                            'description_en' => $episode->description_en,
                            'thumbnail_image' => $episode->thumbnail_image_url,
                            'episode_type' => $episode->episode_type,
                            'youtube_url' => $episode->youtube_url,
                            'audio_file' => $episode->audio_file_url,
                            'spotify_url' => $episode->spotify_url,
                            'apple_podcasts_url' => $episode->apple_podcasts_url,
                            'published_at' => $episode->published_at ? $episode->published_at->format('Y-m-d H:i:s') : null,
                            'status' => $episode->status,
                            'instructor' => $episode->instructor ? [
                                'id' => $episode->instructor->id,
                                'name_ar' => $episode->instructor->name_ar,
                                'name_en' => $episode->instructor->name_en,
                                'image' => $episode->instructor->image_url,
                            ] : null,
                        ];
                    }),
                    'created_at' => $podcast->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $podcast->updated_at->format('Y-m-d H:i:s'),
                ];
            });

        return successJsonResponse($podcasts, 'Podcasts retrieved successfully', $total);
    }

    /**
     * Get a single podcast with episodes, categories, and instructor
     */
    public function show($id)
    {
        $podcast = Podcast::with([
            'episodes.instructor',
            'categories'
        ])->where('status', 'published')->find($id);

        if (!$podcast) {
            return errorJsonResponse('Podcast not found', 404);
        }

        $formattedPodcast = [
            'id' => $podcast->id,
            'title_ar' => $podcast->title_ar,
            'title_en' => $podcast->title_en,
            'description_ar' => $podcast->description_ar,
            'description_en' => $podcast->description_en,
            'cover_image' => $podcast->cover_image_url,
            'podcast_type' => $podcast->podcast_type,
            'status' => $podcast->status,
            'youtube_channel_url' => $podcast->youtube_channel_url,
            'spotify_url' => $podcast->spotify_url,
            'apple_podcasts_url' => $podcast->apple_podcasts_url,
            'categories' => $podcast->categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name_ar' => $category->name_ar,
                    'name_en' => $category->name_en,
                ];
            }),
            'episodes' => $podcast->episodes()->where('status', 'published')->get()->map(function($episode) {
                return [
                    'id' => $episode->id,
                    'title_ar' => $episode->title_ar,
                    'title_en' => $episode->title_en,
                    'description_ar' => $episode->description_ar,
                    'description_en' => $episode->description_en,
                    'thumbnail_image' => $episode->thumbnail_image_url,
                    'episode_type' => $episode->episode_type,
                    'youtube_url' => $episode->youtube_url,
                    'audio_file' => $episode->audio_file_url,
                    'spotify_url' => $episode->spotify_url,
                    'apple_podcasts_url' => $episode->apple_podcasts_url,
                    'published_at' => $episode->published_at ? $episode->published_at->format('Y-m-d H:i:s') : null,
                    'status' => $episode->status,
                    'instructor' => $episode->instructor ? [
                        'id' => $episode->instructor->id,
                        'name_ar' => $episode->instructor->name_ar,
                        'name_en' => $episode->instructor->name_en,
                        'image' => $episode->instructor->image_url,
                    ] : null,
                ];
            }),
            'created_at' => $podcast->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $podcast->updated_at->format('Y-m-d H:i:s'),
        ];

        return successJsonResponse($formattedPodcast, 'Podcast retrieved successfully');
    }
}
