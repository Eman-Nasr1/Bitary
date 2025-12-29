<?php

namespace App\Modules\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Category\Requests\ListAllCategoriesRequest;


class ApiCategoryController extends Controller
{
    public function __construct(private CategoryService $CategoryService) {}

    public function listAllCategories(Request $request)
    {
        $categories = $this->CategoryService->listAllCategories($request->all());
        return successJsonResponse(
            data_get($categories, 'data'),
            __('Categories.success.get_all_Categories'),
            data_get($categories, 'count')
        );
    }

    public function show($id)
    {
        $category = $this->CategoryService->getCategoryById($id);

        if (!$category) {
            return errorJsonResponse(
                __('Categories.errors.not_found'),
                404
            );
        }

        return successJsonResponse(
            $category,
            __('Categories.success.get_single_category')
        );
    }
}
