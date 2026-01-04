@extends('adminlte::page')

@section('title', 'My Jobs')

@section('content_header')
    <h1>My Jobs</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>My Jobs</h3>
            <a href="{{ route('dashboard.provider.jobs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Job
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('dashboard.provider.jobs.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.provider.jobs.index') }}" class="btn btn-light">
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
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
                            <th>Title</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>
                                    <strong>{{ $job->title_ar }}</strong><br>
                                    <small class="text-muted">{{ $job->title_en }}</small>
                                </td>
                                <td>{{ $job->specialization->name_ar ?? 'N/A' }}</td>
                                <td>
                                    @if($job->status == 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($job->status == 'pending')
                                        <span class="badge badge-warning">Pending Review</span>
                                    @elseif($job->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.provider.job-applications.index', ['job_id' => $job->id]) }}" class="badge badge-info">
                                        {{ $job->applications_count ?? $job->applications->count() }} Applications
                                    </a>
                                </td>
                                <td>{{ $job->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.provider.jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($job->status != 'published')
                                            <a href="{{ route('dashboard.provider.jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboard.provider.jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No jobs found. <a href="{{ route('dashboard.provider.jobs.create') }}">Create your first job</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
@stop


