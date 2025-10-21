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

        ];

        if (!isset($typeMap[$request->type])) {
            return errorJsonResponse('نوع غير مدعوم', 422);
        }

        $modelClass = $typeMap[$request->type];
        $item = $modelClass::find($request->id);

        if (!$item) {
            return errorJsonResponse('العنصر غير موجود', 404);
        }

        $alreadyExists = Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => $modelClass,
        ])->exists();

        if ($alreadyExists) {
            return errorJsonResponse('تمت إضافته بالفعل للمفضلة', 409);
        }
        $data =   $user->favoriteItems()->create([
            'favoritable_id' => $item->id,
            'favoritable_type' => $modelClass,
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

        ];

        if (!isset($typeMap[$request->type])) {
            return errorJsonResponse('نوع غير مدعوم', 422);
        }

        $modelClass = $typeMap[$request->type];

        Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $request->id,
            'favoritable_type' => $modelClass,
        ])->delete();

        return successJsonResponse(null, 'تمت الإزالة من المفضلة');
    }
    public function listFavorites(Request $request)
    {


        $user = $request->user();

        // تعريف الـ morphMap اختياري لو محتاجاه
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'medicine' => \App\Models\Medicine::class,
            // ضيفي الكورسات أو أي موديلات تانية هنا
        ]);

        // هتجيبي الـ favoritable models فقط
        $favoriteItems = $user->favoriteItems()
            ->with('favoritable')
            ->get()
            ->pluck('favoritable') // دي النقطة الأساسية
            ->filter(); // لو فيه nulls لأي سبب (مثلاً العنصر اتحذف)

        return successJsonResponse($favoriteItems, 'قائمة المفضلات');
    }
}
