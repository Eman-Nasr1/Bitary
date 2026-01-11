<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StaticPage::query();

        // Search by title
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pages = $query->orderBy('id', 'DESC')->paginate(15);

        return view('dashboard.static-pages.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.static-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaticPageRequest $request)
    {
        $data = $request->validated();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        StaticPage::create($data);

        return redirect()->route('dashboard.static-pages.index')
            ->with('success', 'Static page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = StaticPage::findOrFail($id);
        return view('dashboard.static-pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = StaticPage::findOrFail($id);
        return view('dashboard.static-pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaticPageRequest $request, string $id)
    {
        $page = StaticPage::findOrFail($id);
        $data = $request->validated();

        // Generate slug if not provided and title changed
        if (empty($data['slug']) && $page->isDirty('title_ar')) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $page->update($data);

        return redirect()->route('dashboard.static-pages.index')
            ->with('success', 'Static page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();

        return redirect()->route('dashboard.static-pages.index')
            ->with('success', 'Static page deleted successfully!');
    }
}
