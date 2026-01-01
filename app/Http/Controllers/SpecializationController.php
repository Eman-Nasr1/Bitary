<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Specialization::with('courses');

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        $specializations = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.specializations.index', [
            'specializations' => $specializations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.specializations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationRequest $request)
    {
        $data = $request->validated();

        Specialization::create($data);

        return redirect()->route('dashboard.specializations.index')
            ->with('success', 'Specialization created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialization = Specialization::with('courses')->findOrFail($id);
        return view('dashboard.specializations.show', compact('specialization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specialization = Specialization::findOrFail($id);
        return view('dashboard.specializations.edit', compact('specialization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationRequest $request, string $id)
    {
        $specialization = Specialization::findOrFail($id);
        $data = $request->validated();

        $specialization->update($data);

        return redirect()->route('dashboard.specializations.index')
            ->with('success', 'Specialization updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->delete();

        return redirect()->route('dashboard.specializations.index')
            ->with('success', 'Specialization deleted successfully!');
    }
}
