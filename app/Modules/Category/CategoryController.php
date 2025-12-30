<?php

namespace App\Modules\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\HandlesImages;
use App\Http\Controllers\Controller;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Requests\UpdateCategoryRequest;


class CategoryController extends Controller
{
    use HandlesImages;
    public function __construct(private CategoryService $categoryService) {}

    public function index(Request $request)
    {

        $categories = $this->categoryService->listAllCategories($request->all());

        return view('dashboard.Categories.index', [
            'categories' => $categories['data'],
        ]);
    }


    public function store(StoreCategoryRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }
        $this->categoryService->createCategory($data);


        return redirect()->back()->with('success', 'Category created successfully!');
    }



    public function update(UpdateCategoryRequest $request, $id)
    {

        $category = Category::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($category->image, $request->file('image'));
        } else {
            $data['image'] = $category->image;
        }

        $this->categoryService->updateCategory($id, $data);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }


    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
