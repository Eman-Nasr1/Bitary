<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Traits\HandlesImages;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    use HandlesImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Podcast::with('categories');

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

        $podcasts = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.podcasts.index', [
            'podcasts' => $podcasts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PodcastCategory::all();

        return view('dashboard.podcasts.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePodcastRequest $request)
    {
        $data = $request->validated();

        // Handle cover image upload
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $data['cover_image'] = $this->uploadImage($request->file('cover_image'), 'podcasts');
        }

        $podcast = Podcast::create($data);

        // Attach categories
        if ($request->has('categories')) {
            $podcast->categories()->sync($request->categories);
        }

        return redirect()->route('dashboard.podcasts.index')
            ->with('success', 'Podcast created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $podcast = Podcast::with(['categories', 'episodes.instructor'])->findOrFail($id);
        return view('dashboard.podcasts.show', compact('podcast'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $podcast = Podcast::with('categories')->findOrFail($id);
        $categories = PodcastCategory::all();

        return view('dashboard.podcasts.edit', [
            'podcast' => $podcast,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePodcastRequest $request, string $id)
    {
        $podcast = Podcast::findOrFail($id);
        $data = $request->validated();

        // Handle cover image update
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $data['cover_image'] = $this->updateImage($podcast->cover_image, $request->file('cover_image'), 'podcasts');
        } else {
            $data['cover_image'] = $podcast->cover_image;
        }

        $podcast->update($data);

        // Sync categories
        if ($request->has('categories')) {
            $podcast->categories()->sync($request->categories);
        } else {
            $podcast->categories()->sync([]);
        }

        return redirect()->route('dashboard.podcasts.index')
            ->with('success', 'Podcast updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $podcast = Podcast::findOrFail($id);

        // Delete cover image if exists
        if ($podcast->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($podcast->cover_image);
        }

        $podcast->delete();

        return redirect()->route('dashboard.podcasts.index')
            ->with('success', 'Podcast deleted successfully!');
    }
}
