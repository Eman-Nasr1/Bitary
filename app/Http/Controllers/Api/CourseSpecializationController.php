<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class CourseSpecializationController extends Controller
{
    /**
     * List all course specializations
     */
    public function index(Request $request)
    {
        $query = Specialization::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        $specializations = $query->orderBy('name_en')->get()->map(function($spec) {
            return [
                'id' => $spec->id,
                'name_ar' => $spec->name_ar,
                'name_en' => $spec->name_en,
                'description_ar' => $spec->description_ar,
                'description_en' => $spec->description_en,
                'created_at' => $spec->created_at->toDateTimeString(),
                'updated_at' => $spec->updated_at->toDateTimeString(),
            ];
        });

        return successJsonResponse(
            $specializations,
            __('Course specializations retrieved successfully'),
            $specializations->count()
        );
    }

    /**
     * Show a single course specialization
     */
    public function show($id)
    {
        $specialization = Specialization::find($id);

        if (!$specialization) {
            return errorJsonResponse(
                __('Course specialization not found'),
                404
            );
        }

        $specData = [
            'id' => $specialization->id,
            'name_ar' => $specialization->name_ar,
            'name_en' => $specialization->name_en,
            'description_ar' => $specialization->description_ar,
            'description_en' => $specialization->description_en,
            'created_at' => $specialization->created_at->toDateTimeString(),
            'updated_at' => $specialization->updated_at->toDateTimeString(),
        ];

        return successJsonResponse(
            $specData,
            __('Course specialization retrieved successfully')
        );
    }
}

