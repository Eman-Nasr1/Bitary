@extends('adminlte::page')

@section('title', 'Job Specialization Details')

@section('content_header')
    <h1>Job Specialization Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Specialization #{{ $specialization->id }}</h3>
            <div>
                <a href="{{ route('dashboard.admin.job-specializations.edit', $specialization->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.admin.job-specializations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Name (Arabic)</th>
                            <td>{{ $specialization->name_ar }}</td>
                        </tr>
                        <tr>
                            <th>Name (English)</th>
                            <td>{{ $specialization->name_en }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td><code>{{ $specialization->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($specialization->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jobs Count</th>
                            <td>
                                <span class="badge badge-info">{{ $specialization->jobs_count ?? $specialization->jobs->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $specialization->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $specialization->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <h4>Jobs in this Specialization ({{ $specialization->jobs->count() }})</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Provider</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialization->jobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->title_ar }}</td>
                                <td>{{ $job->provider->name ?? 'N/A' }}</td>
                                <td>
                                    @if($job->status == 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($job->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $job->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.admin.jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No jobs in this specialization yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop


