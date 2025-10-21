<?php

namespace App\Modules\Medicine\Requests;

use App\Modules\Shared\Requests\BaseRequest;

class ListAllMedicinesRequest extends BaseRequest
{
    public function getFilters(): array
    {
        return [
            // الاسم باللغتين
            'name_en'        => 'name_en',
            'name_ar'        => 'name_ar',

            // العنوان / الوصف القصير
            'title_en'       => 'title_en',
            'title_ar'       => 'title_ar',

            // السعر والكمية والخصم
            'price'                => 'price',
            'discount_percentage'  => 'discount_percentage',
            'quantity'             => 'quantity',

            // الوصف باللغتين
            'description_en' => 'description_en',
            'description_ar' => 'description_ar',

            // العلاقات
            'category_id' => 'category_id',
            'seller_id'   => 'seller_id',

            // نوع المنتج
            'product_type_en' => 'product_type_en',
            'product_type_ar' => 'product_type_ar',

            // الشركة المصنعة
            'manufacturer_en' => 'manufacturer_en',
            'manufacturer_ar' => 'manufacturer_ar',
        ];
    }

    public function constructQueryCriteria(array $queryParameters)
    {
        $filters = $this->setFilters(data_get($queryParameters, 'filters'));

        return array_merge(
            $this->constructBaseGetQuery($queryParameters),
            ['filters' => $filters]
        );
    }
}
