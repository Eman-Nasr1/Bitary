@extends('adminlte::page')

@section('title', 'Instructors')

@section('content_header')
    <h1>Instructors</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Instructors</h3>
            <a href="{{ route('dashboard.instructors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Instructor
            </a>
        </div>

        <div class="card-body">
            {{-- Search --}}
            <form action="{{ route('dashboard.instructors.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.instructors.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Instructors Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Courses Count</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr>
                                <td>
                                    @if($instructor->image)
                                        <img src="{{ '/storage/' . $instructor->image }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $instructor->name_ar }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $instructor->name_ar }}</strong>
                                    @if($instructor->name_en)
                                        <br><small class="text-muted">{{ $instructor->name_en }}</small>
                                    @endif
                                </td>
                                <td>{{ $instructor->email ?? '—' }}</td>
                                <td>{{ $instructor->phone ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $instructor->courses->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.instructors.show', $instructor->id) }}" 
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.instructors.edit', $instructor->id) }}" 
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.instructors.destroy', $instructor->id) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this instructor?')">
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
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No instructors found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $instructors->links() }}
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
        console.log("Instructors Index Page Loaded");
    </script>
@stop

