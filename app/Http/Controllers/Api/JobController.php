<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * List all published jobs
     */
    public function index(Request $request)
    {
        $query = Job::with(['specialization', 'provider', 'city'])
            ->published(); // Only published jobs

        // Filter by specialization
        if ($request->has('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }

        // Filter by city
        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by provider
        if ($request->has('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        
        $total = $query->count();
        $jobs = $query->orderBy('published_at', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'title_ar' => $job->title_ar,
                    'title_en' => $job->title_en,
                    'description_ar' => $job->description_ar,
                    'description_en' => $job->description_en,
                    'responsibilities_ar' => $job->responsibilities_ar,
                    'responsibilities_en' => $job->responsibilities_en,
                    'qualifications_ar' => $job->qualifications_ar,
                    'qualifications_en' => $job->qualifications_en,
                    'image' => $job->image ? asset('storage/' . $job->image) : null,
                    'apply_method' => $job->apply_method,
                    'whatsapp_number' => $job->whatsapp_number,
                    'email_address' => $job->email_address,
                    'external_link' => $job->external_link,
                    'published_at' => $job->published_at?->toDateTimeString(),
                    'specialization' => $job->specialization ? [
                        'id' => $job->specialization->id,
                        'name_ar' => $job->specialization->name_ar,
                        'name_en' => $job->specialization->name_en,
                        'slug' => $job->specialization->slug,
                    ] : null,
                    'provider' => $job->provider ? [
                        'id' => $job->provider->id,
                        'name' => $job->provider->name,
                        'email' => $job->provider->email,
                    ] : null,
                    'city' => $job->city ? [
                        'id' => $job->city->id,
                        'name' => $job->city->name,
                    ] : null,
                    'created_at' => $job->created_at->toDateTimeString(),
                    'updated_at' => $job->updated_at->toDateTimeString(),
                ];
            });

        return successJsonResponse(
            $jobs,
            __('Jobs retrieved successfully'),
            $total
        );
    }

    /**
     * Show a single job
     */
    public function show($id)
    {
        $job = Job::with(['specialization', 'provider', 'city'])
            ->published()
            ->find($id);

        if (!$job) {
            return errorJsonResponse(
                __('Job not found'),
                404
            );
        }

        $jobData = [
            'id' => $job->id,
            'title_ar' => $job->title_ar,
            'title_en' => $job->title_en,
            'description_ar' => $job->description_ar,
            'description_en' => $job->description_en,
            'responsibilities_ar' => $job->responsibilities_ar,
            'responsibilities_en' => $job->responsibilities_en,
            'qualifications_ar' => $job->qualifications_ar,
            'qualifications_en' => $job->qualifications_en,
            'image' => $job->image ? asset('storage/' . $job->image) : null,
            'apply_method' => $job->apply_method,
            'whatsapp_number' => $job->whatsapp_number,
            'email_address' => $job->email_address,
            'external_link' => $job->external_link,
            'published_at' => $job->published_at?->toDateTimeString(),
            'specialization' => $job->specialization ? [
                'id' => $job->specialization->id,
                'name_ar' => $job->specialization->name_ar,
                'name_en' => $job->specialization->name_en,
                'slug' => $job->specialization->slug,
            ] : null,
            'provider' => $job->provider ? [
                'id' => $job->provider->id,
                'name' => $job->provider->name,
                'email' => $job->provider->email,
            ] : null,
            'city' => $job->city ? [
                'id' => $job->city->id,
                'name' => $job->city->name,
            ] : null,
            'created_at' => $job->created_at->toDateTimeString(),
            'updated_at' => $job->updated_at->toDateTimeString(),
        ];

        return successJsonResponse(
            $jobData,
            __('Job retrieved successfully')
        );
    }
}

