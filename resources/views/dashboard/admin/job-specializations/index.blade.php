@extends('adminlte::page')

@section('title', __('Job Specializations'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Job Specializations') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Job Specializations') }}</h3>
            <a href="{{ route('dashboard.admin.job-specializations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('dashboard.admin.job-specializations.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by name or slug..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.admin.job-specializations.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name (Arabic)</th>
                            <th>Name (English)</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Jobs Count</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specializations as $specialization)
                            <tr>
                                <td>{{ $specialization->id }}</td>
                                <td>{{ $specialization->name_ar }}</td>
                                <td>{{ $specialization->name_en }}</td>
                                <td><code>{{ $specialization->slug }}</code></td>
                                <td>
                                    @if($specialization->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $specialization->jobs_count ?? $specialization->jobs->count() }}</span>
                                </td>
                                <td>{{ $specialization->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.admin.job-specializations.show', $specialization->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.admin.job-specializations.edit', $specialization->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.admin.job-specializations.destroy', $specialization->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will also delete all associated jobs!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No specializations found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $specializations->links() }}
            </div>
        </div>
    </div>
@stop


