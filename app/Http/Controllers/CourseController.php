<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\StoreInstructorRequest;
use App\Traits\HandlesImages;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use HandlesImages;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructors', 'specializations']);

        // Apply filters
        if ($request->has('filters')) {
            $filters = $request->get('filters', []);
            $query = $query->whereLike($filters);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        $courses = $query->orderBy('id', 'DESC')->paginate(10);

        return view('dashboard.courses.index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instructors = Instructor::all();
        $specializations = Specialization::all();

        return view('dashboard.courses.create', [
            'instructors' => $instructors,
            'specializations' => $specializations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'courses');
        }

        // Handle boolean fields
        $data['certificate_available'] = $request->has('certificate_available') && $request->certificate_available == '1';
        $data['is_free'] = $request->has('is_free') && $request->is_free == '1';

        $course = Course::create($data);

        // Attach instructors
        if ($request->has('instructors')) {
            $course->instructors()->sync($request->instructors);
        }

        // Attach specializations
        if ($request->has('specializations')) {
            $course->specializations()->sync($request->specializations);
        }

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::with(['instructors', 'specializations'])->findOrFail($id);
        return view('dashboard.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::with(['instructors', 'specializations'])->findOrFail($id);
        $instructors = Instructor::all();
        $specializations = Specialization::all();

        return view('dashboard.courses.edit', [
            'course' => $course,
            'instructors' => $instructors,
            'specializations' => $specializations,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, string $id)
    {
        $course = Course::findOrFail($id);
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($course->image, $request->file('image'), 'courses');
        }

        // Handle boolean fields
        // Checkbox fields: if not present in request, set to false
        $data['certificate_available'] = $request->has('certificate_available') && $request->certificate_available == '1';
        $data['is_free'] = $request->has('is_free') && $request->is_free == '1';

        $course->update($data);

        // Sync instructors
        if ($request->has('instructors')) {
            $course->instructors()->sync($request->instructors);
        } else {
            $course->instructors()->sync([]);
        }

        // Sync specializations
        if ($request->has('specializations')) {
            $course->specializations()->sync($request->specializations);
        } else {
            $course->specializations()->sync([]);
        }

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        
        // Delete image if exists
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Store a new instructor via AJAX
     */
    public function storeInstructor(StoreInstructorRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('instructors', 'public');
            }

            $instructor = Instructor::create($data);

            return response()->json([
                'success' => true,
                'instructor' => $instructor,
                'message' => 'Instructor created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create instructor: ' . $e->getMessage()
            ], 422);
        }
    }
}
