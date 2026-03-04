<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsCategoryRequest;
use App\Http\Requests\UpdateNewsCategoryRequest;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCategory::query()->withCount('news');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.news-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.news-categories.create');
    }

    public function store(StoreNewsCategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) $request->boolean('is_active', true);

        NewsCategory::create($data);

        return redirect()->route('dashboard.news-categories.index')
            ->with('success', 'News category created successfully!');
    }

    public function edit(NewsCategory $news_category)
    {
        return view('dashboard.news-categories.edit', ['category' => $news_category]);
    }

    public function update(UpdateNewsCategoryRequest $request, NewsCategory $news_category)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) $request->boolean('is_active', false);

        $news_category->update($data);

        return redirect()->route('dashboard.news-categories.index')
            ->with('success', 'News category updated successfully!');
    }

    public function destroy(NewsCategory $news_category)
    {
        if ($news_category->news()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category. It has associated news.');
        }

        $news_category->delete();

        return redirect()->route('dashboard.news-categories.index')
            ->with('success', 'News category deleted successfully!');
    }
}

