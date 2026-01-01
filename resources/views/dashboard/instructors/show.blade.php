@extends('adminlte::page')

@section('title', 'Instructor Details')

@section('content_header')
    <h1>Instructor Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Instructor #{{ $instructor->id }}</h3>
            <a href="{{ route('dashboard.instructors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($instructor->image)
                        <img src="{{ '/storage/' . $instructor->image }}" 
                            alt="{{ $instructor->name_ar }}" 
                            class="img-fluid rounded mb-3"
                            style="max-width: 100%; height: auto;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" 
                            style="width: 100%; height: 300px;">
                            <i class="fas fa-user fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h5 class="mb-3">Basic Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name (Arabic):</th>
                            <td><strong>{{ $instructor->name_ar }}</strong></td>
                        </tr>
                        <tr>
                            <th>Name (English):</th>
                            <td><strong>{{ $instructor->name_en ?? '—' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $instructor->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $instructor->phone ?? '—' }}</td>
                        </tr>
                        @if($instructor->bio_ar)
                            <tr>
                                <th>Bio (Arabic):</th>
                                <td>{{ $instructor->bio_ar }}</td>
                            </tr>
                        @endif
                        @if($instructor->bio_en)
                            <tr>
                                <th>Bio (English):</th>
                                <td>{{ $instructor->bio_en }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Courses ({{ $instructor->courses->count() }})</h5>
            @if($instructor->courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructor->courses as $course)
                                <tr>
                                    <td>{{ $course->id }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.courses.show', $course->id) }}">
                                            {{ $course->title_en }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-info">{{ ucfirst($course->category) }}</span></td>
                                    <td>
                                        @if($course->status == 'published')
                                            <span class="badge badge-success">Published</span>
                                        @elseif($course->status == 'draft')
                                            <span class="badge badge-warning">Draft</span>
                                        @else
                                            <span class="badge badge-secondary">Archived</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No courses assigned to this instructor.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('dashboard.instructors.edit', $instructor->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Instructor
                </a>
            </div>
        </div>
    </div>
@stop

