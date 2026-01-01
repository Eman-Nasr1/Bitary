<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Traits\HandlesImages;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    use HandlesImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Instructor::with('courses');

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $instructors = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.instructors.index', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'instructors');
        }

        Instructor::create($data);

        return redirect()->route('dashboard.instructors.index')
            ->with('success', 'Instructor created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instructor = Instructor::with('courses')->findOrFail($id);
        return view('dashboard.instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instructor = Instructor::findOrFail($id);
        return view('dashboard.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, string $id)
    {
        $instructor = Instructor::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($instructor->image, $request->file('image'), 'instructors');
        }

        $instructor->update($data);

        return redirect()->route('dashboard.instructors.index')
            ->with('success', 'Instructor updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor = Instructor::findOrFail($id);
        
        // Delete image if exists
        if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
            Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return redirect()->route('dashboard.instructors.index')
            ->with('success', 'Instructor deleted successfully!');
    }
}
