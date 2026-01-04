@extends('adminlte::page')

@section('title', 'Job Details')

@section('content_header')
    <h1>Job Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Job #{{ $job->id }}</h3>
            <div>
                <a href="{{ route('dashboard.admin.jobs.edit', $job->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.admin.jobs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Title (Arabic)</th>
                            <td>{{ $job->title_ar }}</td>
                        </tr>
                        <tr>
                            <th>Title (English)</th>
                            <td>{{ $job->title_en }}</td>
                        </tr>
                        <tr>
                            <th>Provider</th>
                            <td>{{ $job->provider->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Specialization</th>
                            <td>{{ $job->specialization->name_ar ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($job->status == 'published')
                                    <span class="badge badge-success">Published</span>
                                @elseif($job->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($job->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($job->status == 'draft')
                                    <span class="badge badge-secondary">Draft</span>
                                @else
                                    <span class="badge badge-dark">Archived</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Apply Method</th>
                            <td>
                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $job->apply_method)) }}</span>
                            </td>
                        </tr>
                        @if($job->apply_method == 'whatsapp')
                            <tr>
                                <th>WhatsApp Number</th>
                                <td>{{ $job->whatsapp_number }}</td>
                            </tr>
                        @elseif($job->apply_method == 'email')
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $job->email_address }}</td>
                            </tr>
                        @elseif($job->apply_method == 'external_link')
                            <tr>
                                <th>External Link</th>
                                <td><a href="{{ $job->external_link }}" target="_blank">{{ $job->external_link }}</a></td>
                            </tr>
                        @endif
                        @if($job->rejection_reason)
                            <tr>
                                <th>Rejection Reason</th>
                                <td class="text-danger">{{ $job->rejection_reason }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Published At</th>
                            <td>{{ $job->published_at ? $job->published_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $job->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Description</h5>
                    <div class="mb-3">
                        <strong>Arabic:</strong>
                        <p>{{ $job->description_ar ?? 'N/A' }}</p>
                        <strong>English:</strong>
                        <p>{{ $job->description_en ?? 'N/A' }}</p>
                    </div>

                    <h5>Responsibilities</h5>
                    <div class="mb-3">
                        <strong>Arabic:</strong>
                        <p>{{ $job->responsibilities_ar ?? 'N/A' }}</p>
                        <strong>English:</strong>
                        <p>{{ $job->responsibilities_en ?? 'N/A' }}</p>
                    </div>

                    <h5>Qualifications</h5>
                    <div class="mb-3">
                        <strong>Arabic:</strong>
                        <p>{{ $job->qualifications_ar ?? 'N/A' }}</p>
                        <strong>English:</strong>
                        <p>{{ $job->qualifications_en ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <hr>

            <h4>Applications ({{ $job->applications->count() }})</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($job->applications as $application)
                            <tr>
                                <td>{{ $application->id }}</td>
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
                                <td colspan="7" class="text-center">No applications yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop


