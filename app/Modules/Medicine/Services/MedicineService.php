<?php

namespace App\Modules\Medicine\Services;

use App\Models\Medicine;

use App\Modules\Medicine\Resources\MedicineCollection;
use App\Modules\Medicine\Repositories\MedicinesRepository;
use App\Modules\Medicine\Requests\ListAllMedicinesRequest;

class MedicineService
{
    public function __construct(private MedicinesRepository $medicinesRepository) {}
 public function listAllMedicines(array $queryParameters, $relations = [])
{
    // نضيف هذا الجزء لدمج الفلاتر لو ما كانت داخل filters[]
    $filters = $queryParameters['filters'] ?? [];

    // دمج أي مفاتيح خارج filters إلى filters إذا لم يكن هناك key باسم filters
    foreach ($queryParameters as $key => $value) {
        if (!in_array($key, ['limit', 'offset', 'sortBy', 'sort', 'filters', 'lang'])) {
            $filters[$key] = $value;
        }
    }

    $queryParameters['filters'] = $filters;

    $listAllMedicines = (new ListAllMedicinesRequest)->constructQueryCriteria($queryParameters);

    $medicines = $this->medicinesRepository->findAllBy($listAllMedicines, $relations);

    return [
        'data' => new MedicineCollection($medicines['data']),
        'count' => $medicines['count']
    ];
}

    public function constructMedicineModel($request)
    {
        return [
            // الاسم
            'name_en' => $request['name_en'] ?? null,
            'name_ar' => $request['name_ar'] ?? null,

            // العنوان / الوصف القصير
            'title_en' => $request['title_en'] ?? null,
            'title_ar' => $request['title_ar'] ?? null,

            // السعر والخصم والكمية
            'price' => $request['price'] ?? 0,
            'discount_percentage' => $request['discount_percentage'] ?? 0,
            'quantity' => $request['quantity'] ?? 0,

            // التقييم (اختياري)
            'rate' => $request['rate'] ?? null,

            // الوصف
            'description_en' => $request['description_en'] ?? null,
            'description_ar' => $request['description_ar'] ?? null,

            // الوزن والأبعاد
            'weight' => $request['weight'] ?? null,
            'dimensions' => $request['dimensions'] ?? null,

            // العلاقات
            'category_id' => $request['category_id'] ?? null,
            'seller_id' => $request['seller_id'] ?? null,
            'animals' => $request['animals'] ?? null,
            // نوع المنتج
            'product_type_en' => $request['product_type_en'] ?? null,
            'product_type_ar' => $request['product_type_ar'] ?? null,

            // الشركة المصنعة
            'manufacturer_en' => $request['manufacturer_en'] ?? null,
            'manufacturer_ar' => $request['manufacturer_ar'] ?? null,

            // سياسة الاسترجاع
            'return_policy_en' => $request['return_policy_en'] ?? null,
            'return_policy_ar' => $request['return_policy_ar'] ?? null,

            // سياسة الاستبدال
            'exchange_policy_en' => $request['exchange_policy_en'] ?? null,
            'exchange_policy_ar' => $request['exchange_policy_ar'] ?? null,

            // الصورة (لو عندك عمود image في الجدول)
            'image' => $request['image'] ?? null,
        ];
    }
 public function getMedicineById($id)
    {
        $medicine = $this->medicinesRepository->find($id);
        if ($medicine) {
            $medicine->load(['category', 'seller', 'animals']);
        }
        return $medicine;
    }
    public function createMedicine($request)
    {

        $medicineData = $this->constructMedicineModel($request);


        $medicine = $this->medicinesRepository->create($medicineData);

        if (isset($request['animals']) && is_array($request['animals'])) {
            $medicine->animals()->sync($request['animals']);
        }

        return $medicine;
    }


    public function updateMedicine($id, $request)
    {
        $medicineData = $this->constructMedicineModel($request);
        
        // Extract 'animals' from medicineData as it's not a column, it's a relationship
        $animals = $medicineData['animals'] ?? null;
        unset($medicineData['animals']);

        $medicine = $this->medicinesRepository->update($id, $medicineData);
        
        // Update animals relationship if provided in request
        if (isset($request['animals'])) {
            if (is_array($request['animals']) && !empty($request['animals'])) {
                $medicine->animals()->sync($request['animals']);
            } else {
                // If animals is empty array or not set, remove all relationships
                $medicine->animals()->sync([]);
            }
        } elseif (isset($animals) && is_array($animals)) {
            // Fallback to animals from constructMedicineModel
            $medicine->animals()->sync($animals);
        }

        return $medicine;
    }

    public function deleteMedicine($id)
    {
        return $this->medicinesRepository->delete($id);
    }




}
