<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PodcastCategory;
use App\Http\Requests\StorePodcastCategoryRequest;
use App\Http\Requests\UpdatePodcastCategoryRequest;
use Illuminate\Http\Request;

class PodcastCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PodcastCategory::with('podcasts');

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.podcast-categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.podcast-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePodcastCategoryRequest $request)
    {
        $data = $request->validated();

        PodcastCategory::create($data);

        return redirect()->route('dashboard.podcast-categories.index')
            ->with('success', 'Podcast Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = PodcastCategory::with('podcasts')->findOrFail($id);
        return view('dashboard.podcast-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = PodcastCategory::findOrFail($id);
        return view('dashboard.podcast-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePodcastCategoryRequest $request, string $id)
    {
        $category = PodcastCategory::findOrFail($id);
        $data = $request->validated();

        $category->update($data);

        return redirect()->route('dashboard.podcast-categories.index')
            ->with('success', 'Podcast Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = PodcastCategory::findOrFail($id);
        
        // Check if category has podcasts
        if ($category->podcasts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category. It has associated podcasts.');
        }

        $category->delete();

        return redirect()->route('dashboard.podcast-categories.index')
            ->with('success', 'Podcast Category deleted successfully!');
    }
}
