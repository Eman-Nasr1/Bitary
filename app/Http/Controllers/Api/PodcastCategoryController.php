<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PodcastCategory;
use Illuminate\Http\Request;

class PodcastCategoryController extends Controller
{
    /**
     * List all podcast categories
     */
    public function index(Request $request)
    {
        $query = PodcastCategory::withCount('podcasts');

        // Search by name (Arabic or English)
        if ($request->has('search') || $request->has('name')) {
            $search = $request->get('search') ?? $request->get('name');
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        
        $total = $query->count();
        $categories = $query->orderBy('id', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name_ar' => $category->name_ar,
                    'name_en' => $category->name_en,
                    'podcasts_count' => $category->podcasts_count,
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
                ];
            });

        return successJsonResponse($categories, 'Podcast categories retrieved successfully', $total);
    }

    /**
     * Get a single podcast category
     */
    public function show($id)
    {
        $category = PodcastCategory::withCount('podcasts')->find($id);

        if (!$category) {
            return errorJsonResponse('Podcast category not found', 404);
        }

        $formattedCategory = [
            'id' => $category->id,
            'name_ar' => $category->name_ar,
            'name_en' => $category->name_en,
            'podcasts_count' => $category->podcasts_count,
            'created_at' => $category->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
        ];

        return successJsonResponse($formattedCategory, 'Podcast category retrieved successfully');
    }
}
