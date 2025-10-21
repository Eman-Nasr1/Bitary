<?php

namespace App\Modules\Rating;

use App\Models\Seller;
use App\Models\Medicine;
use App\Http\Controllers\Controller;
use App\Modules\Rating\Requests\RateRequest;

class RatingController extends Controller
{
   public function rate(RateRequest $request)
    {
        // resolve الـ model dynamically
        $modelClass = $this->getModelClass($request->rateable_type);

        /** @var Model $rateable */
        $rateable = $modelClass::findOrFail($request->rateable_id);

        // تحقق إن المستخدم ما يعملش أكتر من تقييم لنفس الكيان
        $existingRating = $rateable->ratings()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingRating) {
            $existingRating->update($request->only('rating', 'comment'));
        } else {
            $rateable->ratings()->create([
                'user_id' => $request->user()->id,
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        return response()->json([
            'message' => 'Rating added successfully.',
            'rateable' => [
                'id' => $rateable->id,
                'type' => class_basename($rateable),
                'average_rating' => $rateable->fresh()->rate ?? $rateable->ratings()->avg('rating'),
                'ratings_count' => $rateable->ratings_count ?? $rateable->ratings()->count(),
            ]
        ], 201);
    }

    private function getModelClass(string $type): string
    {
        return match (strtolower($type)) {
            'seller' => \App\Models\Seller::class,
            'medicine' => Medicine::class,

            default => abort(404, 'Invalid rateable type'),
        };
    }
}
