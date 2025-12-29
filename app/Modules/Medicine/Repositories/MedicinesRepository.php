<?php

namespace App\Modules\Medicine\Repositories;

use App\Models\Medicine;
use App\Modules\Shared\Repositories\BaseRepository;

class MedicinesRepository extends BaseRepository
{
    public function __construct(private Medicine $model)
    {
        parent::__construct($model);
    }

    public function findAllBy(array $queryCriteria = [], array $with = []): array
    {
        $query = $this->model->query()->with($with);

        if (!empty($queryCriteria['filters'])) {
            $filters = $queryCriteria['filters'];
            
            // Handle special filters for relationships
            if (isset($filters['animal_id'])) {
                $animalId = $filters['animal_id'];
                $query->whereHas('animals', function ($q) use ($animalId) {
                    $q->where('animals.id', $animalId);
                });
                unset($filters['animal_id']);
            }
            
            if (isset($filters['animal_type_id'])) {
                $animalTypeId = $filters['animal_type_id'];
                $query->whereHas('animals.animalType', function ($q) use ($animalTypeId) {
                    $q->where('animal_types.id', $animalTypeId);
                });
                unset($filters['animal_type_id']);
            }
            
            // Handle category_id filter (direct column)
            if (isset($filters['category_id'])) {
                $categoryId = $filters['category_id'];
                $query->where('category_id', $categoryId);
                unset($filters['category_id']);
            }
            
            // Apply remaining filters using whereLike
            if (!empty($filters)) {
                $query->whereLike($filters);
            }
        }

        $limit = data_get($queryCriteria, 'limit', 10);
        $offset = data_get($queryCriteria, 'offset', 0);
        $sortBy = data_get($queryCriteria, 'sortBy', 'id');
        $sort = data_get($queryCriteria, 'sort', 'DESC');

        return [
            'count' => $query->count(),
            'data' => $query->skip($offset)->take($limit)->orderBy($sortBy, $sort)->get(),
        ];
    }
}
