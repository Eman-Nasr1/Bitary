<?php

namespace App\Modules\Medicine\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicineRequest extends FormRequest
{
    public function rules(): array
    {

      
        return [
            // الاسم
            'name_en' => 'sometimes|string|max:255',
            'name_ar' => 'sometimes|string|max:255',

            // العنوان / الوصف القصير
            'title_en' => 'sometimes|nullable|string|max:255',
            'title_ar' => 'sometimes|nullable|string|max:255',

            // السعر والخصم والكمية
            'price' => 'sometimes|numeric|min:0',
            'discount_percentage' => 'sometimes|nullable|numeric|min:0|max:100',
            'quantity' => 'sometimes|nullable|integer|min:0',

            // التقييم
            'rate' => 'sometimes|nullable|numeric|min:0|max:5',

            // الوصف
            'description_en' => 'sometimes|nullable|string',
            'description_ar' => 'sometimes|nullable|string',

            // الوزن والأبعاد
            'weight' => 'sometimes|nullable|string|max:255',
            'dimensions' => 'sometimes|nullable|string|max:255',

            // العلاقات
            'category_id' => 'sometimes|exists:categories,id',
            'saller_id' => 'sometimes|exists:sallers,id',

            // نوع المنتج
            'product_type_en' => 'sometimes|string|max:255',
            'product_type_ar' => 'sometimes|string|max:255',

            // الشركة المصنعة
            'manufacturer_en' => 'sometimes|nullable|string|max:255',
            'manufacturer_ar' => 'sometimes|nullable|string|max:255',

            // السياسات
            'return_policy_en' => 'sometimes|nullable|string',
            'return_policy_ar' => 'sometimes|nullable|string',
            'exchange_policy_en' => 'sometimes|nullable|string',
            'exchange_policy_ar' => 'sometimes|nullable|string',

            // الصورة (اختيارية)
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
