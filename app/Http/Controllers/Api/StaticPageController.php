<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Display a listing of active static pages.
     */
    public function index(Request $request)
    {
        $query = StaticPage::active();

        // Search by title or slug
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // Order by id (newest first)
        $query->orderBy('id', 'DESC');

        // Paginate
        $perPage = $request->get('per_page', 15);
        $pages = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $pages->map(function ($page) {
                return [
                    'id' => $page->id,
                    'title_ar' => $page->title_ar,
                    'title_en' => $page->title_en,
                    'slug' => $page->slug,
                    'description_ar' => $page->description_ar,
                    'description_en' => $page->description_en,
                    'content_ar' => $page->content_ar,
                    'content_en' => $page->content_en,
                    'status' => $page->status,
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $pages->currentPage(),
                'last_page' => $pages->lastPage(),
                'per_page' => $pages->perPage(),
                'total' => $pages->total(),
            ],
        ]);
    }

    /**
     * Display the specified static page by ID or slug.
     */
    public function show($identifier)
    {
        // Try to find by ID first, then by slug
        $page = StaticPage::active()
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->first();

        if (!$page) {
            return response()->json([
                'status' => 'error',
                'message' => 'Static page not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $page->id,
                'title_ar' => $page->title_ar,
                'title_en' => $page->title_en,
                'slug' => $page->slug,
                'description_ar' => $page->description_ar,
                'description_en' => $page->description_en,
                'content_ar' => $page->content_ar,
                'content_en' => $page->content_en,
                'status' => $page->status,
                'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
