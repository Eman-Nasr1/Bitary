<?php

namespace App\Modules\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCourseController extends Controller
{
    /**
     * Get all courses with instructors and specializations
     */
    public function listAllCourses(Request $request)
    {
        $query = Course::with(['instructors', 'specializations']);

        // Only show published courses by default (unless admin filter is applied)
        if (!$request->has('status')) {
            $query->where('status', 'published');
        } elseif ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply filters
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        // Filter by specialization (course-specializations)
        if ($request->has('specialization_id') || $request->has('course-specializations')) {
            $specializationId = $request->get('specialization_id') ?? $request->get('course-specializations');
            $query->whereHas('specializations', function($q) use ($specializationId) {
                $q->where('specializations.id', $specializationId);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $courses = $query->orderBy('id', 'DESC')->paginate($perPage);

        return successJsonResponse(
            $courses->items(),
            'Courses retrieved successfully',
            $courses->total()
        );
    }

    /**
     * Get single course with instructors and specializations
     */
    public function show($id)
    {
        $course = Course::with(['instructors', 'specializations'])->find($id);

        if (!$course) {
            return errorJsonResponse(
                'Course not found',
                404
            );
        }

        // Check if course is published (unless admin)
        if ($course->status !== 'published') {
            return errorJsonResponse(
                'Course is not available',
                404
            );
        }

        return successJsonResponse(
            $course,
            'Course retrieved successfully'
        );
    }
}

