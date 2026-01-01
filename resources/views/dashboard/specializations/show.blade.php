@extends('adminlte::page')

@section('title', 'Specialization Details')

@section('content_header')
    <h1>Specialization Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Specialization #{{ $specialization->id }}</h3>
            <a href="{{ route('dashboard.specializations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <h5 class="mb-3">Basic Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Name (Arabic):</th>
                    <td><strong>{{ $specialization->name_ar }}</strong></td>
                </tr>
                <tr>
                    <th>Name (English):</th>
                    <td><strong>{{ $specialization->name_en }}</strong></td>
                </tr>
                @if($specialization->description_ar)
                    <tr>
                        <th>Description (Arabic):</th>
                        <td>{{ $specialization->description_ar }}</td>
                    </tr>
                @endif
                @if($specialization->description_en)
                    <tr>
                        <th>Description (English):</th>
                        <td>{{ $specialization->description_en }}</td>
                    </tr>
                @endif
            </table>

            <hr>

            <h5 class="mb-3">Courses ({{ $specialization->courses->count() }})</h5>
            @if($specialization->courses->count() > 0)
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
                            @foreach($specialization->courses as $course)
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
                <p class="text-muted">No courses assigned to this specialization.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('dashboard.specializations.edit', $specialization->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Specialization
                </a>
            </div>
        </div>
    </div>
@stop

