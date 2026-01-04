<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with(['job.specialization', 'job.provider']);

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

        return view('dashboard.admin.job-applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = JobApplication::with(['job.specialization', 'job.provider'])->findOrFail($id);
        return view('dashboard.admin.job-applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        
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

    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('dashboard.admin.job-applications.index')
            ->with('success', 'Application deleted successfully!');
    }
}
