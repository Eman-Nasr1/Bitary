<?php

namespace App\Modules\AnimalType\Requests;

use App\Modules\Shared\Requests\BaseRequest;

class ListAllAnimalTypesRequest extends BaseRequest
{
    public function getFilters(): array
    {
        return [
            'name_en' =>  'name_en',
            'name_ar' =>  'name_ar',
        ];
    }

    public function constructQueryCriteria(array $queryParameters)
    {
        $filters = $this->setFilters(data_get($queryParameters, 'filters'));
        return array_merge($this->constructBaseGetQuery($queryParameters), ['filters' => $filters]);
    }
}

