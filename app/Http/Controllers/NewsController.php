<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Traits\HandlesImages;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use HandlesImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search by title
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.news.index', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        // Handle cover image upload
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $data['cover_image'] = $this->uploadImage($request->file('cover_image'), 'news');
        }

        // Handle tags array
        if ($request->has('tags') && is_array($request->tags)) {
            $data['tags'] = array_filter($request->tags);
        } else {
            $data['tags'] = null;
        }

        // Set published_at if status is published and not provided
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        News::create($data);

        return redirect()->route('dashboard.news.index')
            ->with('success', 'News created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::with('approvedComments.user')->findOrFail($id);
        return view('dashboard.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);
        return view('dashboard.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, string $id)
    {
        $news = News::findOrFail($id);
        $data = $request->validated();

        // Handle cover image update
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $data['cover_image'] = $this->updateImage($news->cover_image, $request->file('cover_image'), 'news');
        } else {
            $data['cover_image'] = $news->cover_image;
        }

        // Handle tags array
        if ($request->has('tags') && is_array($request->tags)) {
            $data['tags'] = array_filter($request->tags);
        } elseif (!$request->has('tags')) {
            $data['tags'] = null;
        } else {
            $data['tags'] = $news->tags;
        }

        // Set published_at if status is published and not provided
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $news->published_at ?? now();
        }

        $news->update($data);

        return redirect()->route('dashboard.news.index')
            ->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        // Delete cover image if exists
        if ($news->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($news->cover_image);
        }

        $news->delete();

        return redirect()->route('dashboard.news.index')
            ->with('success', 'News deleted successfully!');
    }
}
