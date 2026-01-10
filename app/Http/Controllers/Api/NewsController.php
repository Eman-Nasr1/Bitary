<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of published news.
     */
    public function index(Request $request)
    {
        $query = News::published()->with('approvedComments');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search by title
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        // Order by published_at (newest first)
        $query->orderBy('published_at', 'DESC');

        // Paginate
        $perPage = $request->get('per_page', 15);
        $news = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $news->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title_ar' => $item->title_ar,
                    'title_en' => $item->title_en,
                    'summary_ar' => $item->summary_ar,
                    'summary_en' => $item->summary_en,
                    'cover_image' => $item->cover_image_url,
                    'category' => $item->category,
                    'category_label' => ucfirst(str_replace('_', ' ', $item->category)),
                    'tags' => $item->tags ?? [],
                    'published_at' => $item->published_at ? $item->published_at->format('Y-m-d H:i:s') : null,
                    'author_name' => $item->author_name,
                    'comments_count' => $item->approvedComments->count(),
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ],
        ]);
    }

    /**
     * Display the specified news with approved comments.
     */
    public function show($id)
    {
        $news = News::published()
            ->with(['approvedComments' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }, 'approvedComments.user'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $news->id,
                'title_ar' => $news->title_ar,
                'title_en' => $news->title_en,
                'summary_ar' => $news->summary_ar,
                'summary_en' => $news->summary_en,
                'content_ar' => $news->content_ar,
                'content_en' => $news->content_en,
                'cover_image' => $news->cover_image_url,
                'category' => $news->category,
                'category_label' => ucfirst(str_replace('_', ' ', $news->category)),
                'tags' => $news->tags ?? [],
                'published_at' => $news->published_at ? $news->published_at->format('Y-m-d H:i:s') : null,
                'author_name' => $news->author_name,
                'comments' => $news->approvedComments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user_name' => $comment->user_name ?? ($comment->user->name ?? 'Anonymous'),
                        'comment' => $comment->comment,
                        'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'comments_count' => $news->approvedComments->count(),
                'created_at' => $news->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Store a new comment on a news article.
     */
    public function addComment(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'comment' => 'required|string|max:1000',
            'user_name' => 'nullable|string|max:255', // Optional if user is logged in
        ]);

        // Verify that the news exists and is published
        $news = News::published()->findOrFail($id);

        $user = auth()->user();

        // Create the comment
        $comment = NewsComment::create([
            'news_id' => $id,
            'user_id' => $user ? $user->id : null,
            'user_name' => $request->user_name ?? ($user ? ($user->name ?? 'Anonymous') : 'Anonymous'),
            'comment' => $request->comment,
            'status' => 'pending', // Comments need admin approval
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment submitted successfully. It will be reviewed by an admin before being published.',
            'data' => [
                'id' => $comment->id,
                'news_id' => $comment->news_id,
                'comment' => $comment->comment,
                'user_name' => $comment->user_name,
                'status' => $comment->status,
                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }
}
