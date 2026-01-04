<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobSpecialization;
use App\Models\City;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Traits\HandlesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    use HandlesImages;
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
        
        $query = Job::with(['specialization', 'applications'])
            ->where('provider_id', $providerId);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        $jobs = $query->with(['city'])->orderBy('created_at', 'DESC')->paginate(15);
        $specializations = JobSpecialization::active()->get();

        return view('dashboard.provider.jobs.index', compact('jobs', 'specializations'));
    }

    public function create()
    {
        $specializations = JobSpecialization::active()->get();
        $cities = City::orderBy('name')->get();
        return view('dashboard.provider.jobs.create', compact('specializations', 'cities'));
    }

    public function store(StoreJobRequest $request)
    {
        $providerId = Auth::guard('web')->user()->id;
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'jobs');
        }
        
        // Provider jobs start as pending
        $data['status'] = 'pending';
        $data['provider_id'] = $providerId;

        Job::create($data);

        return redirect()->route('dashboard.provider.jobs.index')
            ->with('success', 'Job created successfully! It will be reviewed by an administrator.');
    }

    public function show($id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $job = Job::with(['specialization', 'city', 'applications'])
            ->where('provider_id', $providerId)
            ->findOrFail($id);
            
        return view('dashboard.provider.jobs.show', compact('job'));
    }

    public function edit($id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $job = Job::where('provider_id', $providerId)->findOrFail($id);
        
        // Don't allow editing published jobs
        if ($job->status == 'published') {
            return redirect()->route('dashboard.provider.jobs.index')
                ->with('error', 'Cannot edit published jobs. Please contact admin.');
        }
        
        $specializations = JobSpecialization::active()->get();
        $cities = City::orderBy('name')->get();
        return view('dashboard.provider.jobs.edit', compact('job', 'specializations', 'cities'));
    }

    public function update(UpdateJobRequest $request, $id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $job = Job::where('provider_id', $providerId)->findOrFail($id);
        
        // Don't allow editing published jobs
        if ($job->status == 'published') {
            return redirect()->route('dashboard.provider.jobs.index')
                ->with('error', 'Cannot edit published jobs. Please contact admin.');
        }

        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($job->image, $request->file('image'), 'jobs');
        } else {
            // Keep existing image if no new image uploaded
            $data['image'] = $job->image;
        }
        
        // If status changed to draft, reset to pending on update
        if (isset($data['status']) && $data['status'] == 'draft') {
            $data['status'] = 'pending';
        } else {
            // Keep current status or set to pending
            $data['status'] = $job->status == 'pending' ? 'pending' : 'pending';
        }
        
        // Ensure provider_id doesn't change
        $data['provider_id'] = $providerId;

        $job->update($data);

        return redirect()->route('dashboard.provider.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $providerId = Auth::guard('web')->user()->id;
        $job = Job::where('provider_id', $providerId)->findOrFail($id);
        
        // Don't allow deleting published jobs
        if ($job->status == 'published') {
            return redirect()->route('dashboard.provider.jobs.index')
                ->with('error', 'Cannot delete published jobs. Please contact admin.');
        }
        
        $job->delete();

        return redirect()->route('dashboard.provider.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }
}
