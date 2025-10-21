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
            $query->whereLike($queryCriteria['filters']);
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
