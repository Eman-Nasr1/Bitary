<?php

namespace App\Modules\Favorite;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{


    public function addToFavorite(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
        ]);

        $user =  $request->user();

        $typeMap = [
            'medicine' => \App\Models\Medicine::class,
            'product' => \App\Models\Medicine::class, // Medicine is also called product in API
            'course' => \App\Models\Course::class,
        ];

        if (!isset($typeMap[$request->type])) {
            return errorJsonResponse('نوع غير مدعوم', 422);
        }

        $modelClass = $typeMap[$request->type];
        $item = $modelClass::find($request->id);

        if (!$item) {
            return errorJsonResponse('العنصر غير موجود', 404);
        }

        // Use the type string (medicine, product, course) instead of full class name
        $favoritableType = $request->type === 'product' ? 'medicine' : $request->type;

        $alreadyExists = Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => $favoritableType,
        ])->exists();

        if ($alreadyExists) {
            return errorJsonResponse('تمت إضافته بالفعل للمفضلة', 409);
        }
        $data =   $user->favoriteItems()->create([
            'favoritable_id' => $item->id,
            'favoritable_type' => $favoritableType, // Use 'medicine' or 'course' instead of full class name
        ]);

        return successJsonResponse($data, 'تمت الإضافة إلى المفضلة بنجاح');
    }
    public function removeFromFavorite(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
        ]);

        $user =  $request->user();

        $typeMap = [
            'medicine' => \App\Models\Medicine::class,
            'product' => \App\Models\Medicine::class, // Medicine is also called product in API
            'course' => \App\Models\Course::class,
        ];

        if (!isset($typeMap[$request->type])) {
            return errorJsonResponse('نوع غير مدعوم', 422);
        }

        // Use the type string (medicine, product, course) instead of full class name
        $favoritableType = $request->type === 'product' ? 'medicine' : $request->type;

        Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $request->id,
            'favoritable_type' => $favoritableType, // Use 'medicine' or 'course' instead of full class name
        ])->delete();

        return successJsonResponse(null, 'تمت الإزالة من المفضلة');
    }
    public function listFavorites(Request $request)
    {
        $user = $request->user();

        // Get all favorites with their related models
        $favorites = $user->favoriteItems()
            ->with('favoritable')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Separate favorites by type to eager load relationships
        $medicineIds = [];
        $courseIds = [];
        
        foreach ($favorites as $favorite) {
            // Handle both old format (App\Models\Medicine) and new format (medicine)
            $favoritableType = $favorite->favoritable_type;
            if ($favoritableType === 'medicine' || 
                $favoritableType === 'product' || 
                $favoritableType === \App\Models\Medicine::class) {
                $medicineIds[] = $favorite->favoritable_id;
            } elseif ($favoritableType === 'course' || 
                      $favoritableType === \App\Models\Course::class) {
                $courseIds[] = $favorite->favoritable_id;
            }
        }

        // Eager load relationships
        $medicines = \App\Models\Medicine::with('category')->whereIn('id', $medicineIds)->get()->keyBy('id');
        $courses = \App\Models\Course::whereIn('id', $courseIds)->get()->keyBy('id');

        // Group favorites by type and format the response
        $formattedFavorites = $favorites->map(function ($favorite) use ($medicines, $courses) {
            $item = null;
            $type = 'unknown';

            // Get the item from eager loaded collections
            // Handle both old format (App\Models\Medicine) and new format (medicine)
            $favoritableType = $favorite->favoritable_type;
            if ($favoritableType === 'medicine' || 
                $favoritableType === 'product' || 
                $favoritableType === \App\Models\Medicine::class) {
                $item = $medicines->get($favorite->favoritable_id);
                $type = 'product';
            } elseif ($favoritableType === 'course' || 
                      $favoritableType === \App\Models\Course::class) {
                $item = $courses->get($favorite->favoritable_id);
                $type = 'course';
            }
            
            if (!$item) {
                return null; // Skip deleted items
            }

            // Format the response based on type
            $formatted = [
                'id' => $item->id,
                'type' => $type,
                'favorite_id' => $favorite->id,
                'created_at' => $favorite->created_at->format('Y-m-d H:i:s'),
            ];

            // Add type-specific data
            if ($item instanceof \App\Models\Medicine) {
                $formatted['product'] = [
                    'id' => $item->id,
                    'name_ar' => $item->name_ar,
                    'name_en' => $item->name_en,
                    'title_ar' => $item->title_ar,
                    'title_en' => $item->title_en,
                    'price' => $item->price,
                    'discount_percentage' => $item->discount_percentage,
                    'final_price' => $item->final_price,
                    'image_url' => $item->image_url,
                    'description_ar' => $item->description_ar,
                    'description_en' => $item->description_en,
                    'rate' => $item->rate,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'name_ar' => $item->category->name_ar,
                        'name_en' => $item->category->name_en,
                    ] : null,
                ];
            } elseif ($item instanceof \App\Models\Course) {
                $formatted['course'] = [
                    'id' => $item->id,
                    'title_ar' => $item->title_ar,
                    'title_en' => $item->title_en,
                    'image_url' => $item->image_url,
                    'overview_ar' => $item->overview_ar,
                    'overview_en' => $item->overview_en,
                    'description_ar' => $item->description_ar,
                    'description_en' => $item->description_en,
                    'price' => $item->price,
                    'currency' => $item->currency,
                    'discount_percent' => $item->discount_percent,
                    'is_free' => $item->is_free,
                    'category' => $item->category,
                    'level' => $item->level,
                    'duration_weeks' => $item->duration_weeks,
                    'certificate_available' => $item->certificate_available,
                ];
            }

            return $formatted;
        })->filter(); // Remove null items (deleted favorites)

        return successJsonResponse(
            $formattedFavorites->values()->toArray(),
            'قائمة المفضلات',
            $formattedFavorites->count()
        );
    }
}
