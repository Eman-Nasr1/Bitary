<?php

namespace App\Modules\Category\Services;

use App\Models\Category;

use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Repositories\CategoriesRepository;
use App\Modules\Category\Requests\ListAllCategoriesRequest;

class CategoryService
{
    public function __construct(private CategoriesRepository $categoriesRepository)
    {
    }
  public function listAllCategories(array $queryParameters)
    {

        $listAllCategories= (new ListAllCategoriesRequest)->constructQueryCriteria($queryParameters);

        $categories= $this->categoriesRepository->findAllBy($listAllCategories );
        return [
            'data' => new CategoryCollection($categories['data']),
            'count' => $categories['count']
        ];
    }

    public function constructCategoryModel($request)
    {
        $CategoryModel = [
            'name_en' => $request['name_en'],
            'name_ar' => $request['name_ar'],
            'image' => $request['image'],


        ];
        return $CategoryModel;
    }
    public function createCategory($request)
    {

        $category = $this->constructCategoryModel($request);

        return $this->categoriesRepository->create($category);
    }

    public function updateCategory($id, $request)
    {


        $category = $this->constructCategoryModel($request);

        return $this->categoriesRepository->update($id, $category);
    }

    public function deleteCategory($id)
    {
        return $this->categoriesRepository->delete($id);
    }



    public function getCategoryById($id)
    {
        return $this->categoriesRepository->find($id);
    }



}
