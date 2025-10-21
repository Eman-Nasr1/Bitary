<?php

namespace App\Modules\Medicine\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreMedicineRequest extends FormRequest
{
    public function rules(): array
    {
      return [
            // الاسم
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',

            // العنوان / الوصف القصير
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',

            // السعر والخصم والكمية
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'nullable|integer|min:0',

            // التقييم (اختياري)
            'rate' => 'nullable|numeric|min:0|max:5',

            // الوصف
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',

            // الوزن والأبعاد
            'weight' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',

            // العلاقات
            'category_id' => 'required|exists:categories,id',
            'saller_id' => 'required|exists:sallers,id',

            // نوع المنتج
            'product_type_en' => 'required|string|max:255',
            'product_type_ar' => 'required|string|max:255',

            // الشركة المصنعة
            'manufacturer_en' => 'nullable|string|max:255',
            'manufacturer_ar' => 'nullable|string|max:255',

            // سياسات الاسترجاع والاستبدال
            'return_policy_en' => 'nullable|string',
            'return_policy_ar' => 'nullable|string',
            'exchange_policy_en' => 'nullable|string',
            'exchange_policy_ar' => 'nullable|string',

            // الصورة
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

