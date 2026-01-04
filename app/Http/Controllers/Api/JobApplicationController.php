<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    /**
     * Apply for a job
     */
    public function apply(Request $request, $jobId)
    {
        $user = auth('sanctum')->user();
        
        if (!$user) {
            return errorJsonResponse('Unauthenticated', 401);
        }

        $job = Job::where('status', 'published')->find($jobId);
        
        if (!$job) {
            return errorJsonResponse('Job not found or not published', 404);
        }

        // Check if user already applied
        $existingApplication = JobApplication::where('job_id', $jobId)
            ->where('email', $request->input('email'))
            ->first();

        if ($existingApplication) {
            return errorJsonResponse('You have already applied for this job', 422);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'current_location' => 'nullable|string|max:255',
            'cover_letter' => 'nullable|string',
            'extra_info' => 'nullable|string',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        $data = $request->only([
            'full_name', 'phone', 'email', 'current_location', 
            'cover_letter', 'extra_info'
        ]);

        $data['job_id'] = $jobId;
        $data['provider_id'] = $job->provider_id;
        $data['status'] = 'new';

        // Handle CV file upload
        if ($request->hasFile('cv_file') && $request->file('cv_file')->isValid()) {
            $cvFile = $request->file('cv_file');
            $fileName = time() . '_' . uniqid() . '.' . $cvFile->getClientOriginalExtension();
            $data['cv_file'] = $cvFile->storeAs('job-applications', $fileName, 'public');
        }

        $application = JobApplication::create($data);

        return successJsonResponse([
            'id' => $application->id,
            'job_id' => $application->job_id,
            'full_name' => $application->full_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'status' => $application->status,
            'created_at' => $application->created_at,
        ], 'Application submitted successfully!');
    }

    /**
     * Get user's applications
     */
    public function myApplications(Request $request)
    {
        $user = auth('sanctum')->user();
        
        if (!$user) {
            return errorJsonResponse('Unauthenticated', 401);
        }

        $query = JobApplication::with(['job.specialization', 'job.provider'])
            ->where('email', $user->email);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'DESC')->paginate(15);

        $formattedApplications = $applications->map(function($application) {
            return [
                'id' => $application->id,
                'job' => [
                    'id' => $application->job->id,
                    'title_ar' => $application->job->title_ar,
                    'title_en' => $application->job->title_en,
                    'specialization' => $application->job->specialization->name_ar ?? null,
                ],
                'full_name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'status' => $application->status,
                'created_at' => $application->created_at,
            ];
        });

        return successJsonResponse($formattedApplications, 'Applications retrieved successfully', $applications->total());
    }
}
