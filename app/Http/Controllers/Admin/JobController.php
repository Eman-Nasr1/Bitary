<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobSpecialization;
use App\Models\User;
use App\Models\City;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Traits\HandlesImages;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use HandlesImages;
    public function index(Request $request)
    {
        $query = Job::with(['specialization', 'provider']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by provider
        if ($request->has('provider_id') && $request->provider_id != '') {
            $query->where('provider_id', $request->provider_id);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        $jobs = $query->with(['city'])->withCount('applications')->orderBy('created_at', 'DESC')->paginate(15);
        $specializations = JobSpecialization::active()->get();
        $providers = User::where(function($q) {
            $q->where('role', 'provider')->orWhere('is_provider', true);
        })->get();

        return view('dashboard.admin.jobs.index', compact('jobs', 'specializations', 'providers'));
    }

    public function create()
    {
        $specializations = JobSpecialization::active()->get();
        $providers = User::where('role', 'provider')->orWhere('is_provider', true)->get();
        $cities = City::orderBy('name')->get();
        return view('dashboard.admin.jobs.create', compact('specializations', 'providers', 'cities'));
    }

    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'jobs');
        }
        
        // Admin creates jobs as published by default
        if (!isset($data['status'])) {
            $data['status'] = 'published';
        }
        
        if ($data['status'] == 'published') {
            $data['published_at'] = now();
        }

        // If provider_id not set, use admin's choice or default
        if (!isset($data['provider_id'])) {
            $data['provider_id'] = $request->input('provider_id');
        }

        Job::create($data);

        return redirect()->route('dashboard.admin.jobs.index')
            ->with('success', 'Job created successfully!');
    }

    public function show($id)
    {
        $job = Job::with(['specialization', 'provider', 'city', 'applications'])->findOrFail($id);
        return view('dashboard.admin.jobs.show', compact('job'));
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $specializations = JobSpecialization::active()->get();
        $providers = User::where('role', 'provider')->orWhere('is_provider', true)->get();
        $cities = City::orderBy('name')->get();
        return view('dashboard.admin.jobs.edit', compact('job', 'specializations', 'providers', 'cities'));
    }

    public function update(UpdateJobRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($job->image, $request->file('image'), 'jobs');
        } else {
            // Keep existing image if no new image uploaded
            $data['image'] = $job->image;
        }

        // Handle status changes
        if (isset($data['status']) && $data['status'] == 'published' && $job->status != 'published') {
            $data['published_at'] = now();
        }

        if (isset($data['status']) && $data['status'] == 'rejected' && !isset($data['rejection_reason'])) {
            $data['rejection_reason'] = $request->input('rejection_reason');
        }

        $job->update($data);

        return redirect()->route('dashboard.admin.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->route('dashboard.admin.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    public function approve($id)
    {
        $job = Job::findOrFail($id);
        
        if ($job->status != 'pending') {
            return redirect()->back()->with('error', 'Only pending jobs can be approved.');
        }

        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Job approved and published successfully!');
    }

    public function reject(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        
        if ($job->status != 'pending') {
            return redirect()->back()->with('error', 'Only pending jobs can be rejected.');
        }

        $job->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->back()->with('success', 'Job rejected successfully!');
    }
}
