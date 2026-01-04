<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if (!$user || (!$user->isProvider() && !$user->isAdmin())) {
                abort(403, 'Unauthorized. Provider access required.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $providerId = Auth::guard('web')->user()->id;
        
        $query = JobApplication::with(['job.specialization'])
            ->forProvider($providerId);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by job
        if ($request->has('job_id') && $request->job_id != '') {
            $query->where('job_id', $request->job_id);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'DESC')->paginate(15);

        return view('dashboard.provider.job-applications.index', compact('applications'));
    }

    public function show($id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $application = JobApplication::with(['job.specialization'])
            ->forProvider($providerId)
            ->findOrFail($id);
            
        return view('dashboard.provider.job-applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $application = JobApplication::with('job')
            ->forProvider($providerId)
            ->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:new,reviewed,accepted,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }
}
