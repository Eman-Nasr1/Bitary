<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobSpecialization;
use Illuminate\Http\Request;

class JobSpecializationController extends Controller
{
    /**
     * List all active job specializations
     */
    public function index(Request $request)
    {
        $query = JobSpecialization::active();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        $specializations = $query->orderBy('name_en')->get()->map(function($spec) {
            return [
                'id' => $spec->id,
                'name_ar' => $spec->name_ar,
                'name_en' => $spec->name_en,
                'slug' => $spec->slug,
                'is_active' => $spec->is_active,
                'created_at' => $spec->created_at->toDateTimeString(),
                'updated_at' => $spec->updated_at->toDateTimeString(),
            ];
        });

        return successJsonResponse(
            $specializations,
            __('Job specializations retrieved successfully'),
            $specializations->count()
        );
    }

    /**
     * Show a single job specialization
     */
    public function show($id)
    {
        $specialization = JobSpecialization::active()->find($id);

        if (!$specialization) {
            return errorJsonResponse(
                __('Job specialization not found'),
                404
            );
        }

        $specData = [
            'id' => $specialization->id,
            'name_ar' => $specialization->name_ar,
            'name_en' => $specialization->name_en,
            'slug' => $specialization->slug,
            'is_active' => $specialization->is_active,
            'created_at' => $specialization->created_at->toDateTimeString(),
            'updated_at' => $specialization->updated_at->toDateTimeString(),
        ];

        return successJsonResponse(
            $specData,
            __('Job specialization retrieved successfully')
        );
    }
}

