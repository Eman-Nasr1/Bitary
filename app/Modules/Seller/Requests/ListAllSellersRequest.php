<?php

namespace App\Modules\Seller\Requests;

use App\Modules\Shared\Requests\BaseRequest;

class ListAllSellersRequest extends BaseRequest
{
    public function getFilters(): array
    {
        return [

            'name_en' =>  'name_en',
            'name_ar' =>  'name_ar',
            'phone' =>  'phone',
            'availability' =>  'availability',
            'description_en' =>  'description_en',
            'description_ar' =>  'description_ar',
        ];
    }

    public function constructQueryCriteria(array $queryParameters)
    {
        $filters = $this->setFilters(data_get($queryParameters, 'filters'));
        return array_merge($this->constructBaseGetQuery($queryParameters), ['filters' => $filters]);
    }
}
