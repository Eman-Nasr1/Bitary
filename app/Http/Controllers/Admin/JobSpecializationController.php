<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobSpecialization;
use App\Http\Requests\StoreJobSpecializationRequest;
use Illuminate\Http\Request;

class JobSpecializationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobSpecialization::withCount('jobs');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        $specializations = $query->orderBy('name_ar', 'ASC')->paginate(15);

        return view('dashboard.admin.job-specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('dashboard.admin.job-specializations.create');
    }

    public function store(StoreJobSpecializationRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name_en'] ?? $data['name_ar']);
        }

        JobSpecialization::create($data);

        return redirect()->route('dashboard.admin.job-specializations.index')
            ->with('success', 'Job specialization created successfully!');
    }

    public function show($id)
    {
        $specialization = JobSpecialization::with(['jobs.provider', 'jobs.specialization'])->findOrFail($id);
        return view('dashboard.admin.job-specializations.show', compact('specialization'));
    }

    public function edit($id)
    {
        $specialization = JobSpecialization::findOrFail($id);
        return view('dashboard.admin.job-specializations.edit', compact('specialization'));
    }

    public function update(Request $request, $id)
    {
        $specialization = JobSpecialization::findOrFail($id);
        
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_specializations,slug,' . $id,
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;

        $specialization->update($data);

        return redirect()->route('dashboard.admin.job-specializations.index')
            ->with('success', 'Job specialization updated successfully!');
    }

    public function destroy($id)
    {
        $specialization = JobSpecialization::findOrFail($id);
        $specialization->delete();

        return redirect()->route('dashboard.admin.job-specializations.index')
            ->with('success', 'Job specialization deleted successfully!');
    }
}
