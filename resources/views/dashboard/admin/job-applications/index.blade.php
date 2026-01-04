@extends('adminlte::page')

@section('title', 'Job Applications')

@section('content_header')
    <h1>Job Applications</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>All Job Applications</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.admin.job-applications.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by name, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="job_id" class="form-control" 
                            placeholder="Job ID" value="{{ request('job_id') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.admin.job-applications.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->id }}</td>
                                <td>
                                    <strong>{{ $application->job->title_ar ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">Job #{{ $application->job_id }}</small>
                                </td>
                                <td>{{ $application->full_name }}</td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->phone }}</td>
                                <td>
                                    @if($application->status == 'new')
                                        <span class="badge badge-primary">New</span>
                                    @elseif($application->status == 'reviewed')
                                        <span class="badge badge-info">Reviewed</span>
                                    @elseif($application->status == 'accepted')
                                        <span class="badge badge-success">Accepted</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $application->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.admin.job-applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No applications found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
@stop


