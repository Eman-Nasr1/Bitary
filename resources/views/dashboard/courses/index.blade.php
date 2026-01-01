@extends('adminlte::page')

@section('title', 'Courses')

@section('content_header')
    <h1>Courses</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Courses</h3>
            <a href="{{ route('dashboard.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Course
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.courses.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="filters[status]" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('filters.status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('filters.status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ request('filters.status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filters[category]" class="form-control">
                            <option value="">All Categories</option>
                            <option value="vets" {{ request('filters.category') == 'vets' ? 'selected' : '' }}>Vets</option>
                            <option value="students" {{ request('filters.category') == 'students' ? 'selected' : '' }}>Students</option>
                            <option value="breeders" {{ request('filters.category') == 'breeders' ? 'selected' : '' }}>Breeders</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.courses.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Courses Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th style="width: 80px;">ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Level</th>
                            <th>Instructors</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    @if($course->image_url)
                                        <img src="{{ $course->image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $course->title_en }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $course->id }}</span></td>
                                <td>
                                    <strong>{{ $course->title_en }}</strong>
                                    @if($course->title_ar)
                                        <br><small class="text-muted">{{ $course->title_ar }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($course->category) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($course->level) }}</span>
                                </td>
                                <td>
                                    @forelse($course->instructors as $instructor)
                                        <span class="badge badge-primary">{{ $instructor->name_ar }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if($course->is_free)
                                        <span class="badge badge-success">Free</span>
                                    @else
                                        {{ $course->price }} {{ $course->currency }}
                                        @if($course->discount_percent)
                                            <br><small class="text-success">-{{ $course->discount_percent }}%</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($course->status == 'published')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Published</span>
                                    @elseif($course->status == 'draft')
                                        <span class="badge badge-warning"><i class="fas fa-edit"></i> Draft</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-archive"></i> Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.courses.show', $course->id) }}" 
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.courses.edit', $course->id) }}" 
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.courses.destroy', $course->id) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this course?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No courses found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #343a40;
            color: white;
            font-weight: 600;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        .img-thumbnail {
            border-radius: 4px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Courses Index Page Loaded");
    </script>
@stop

