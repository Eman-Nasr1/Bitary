<?php

namespace App\Modules\Location\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isArabic = app()->getLocale() === 'ar';

        return $this->collection->map(function ($city) use ($isArabic) {
            $nameAr = $city->name_ar ?? $city->name;
            $nameEn = $city->name_en ?? $city->name;

            return [
                'id' => $city->id,
                'name' => $isArabic ? $nameAr : $nameEn,
                'name_ar' => $nameAr,
                'name_en' => $nameEn,
            ];
        })->all();
    }
}

