@extends('adminlte::page')

@section('title', 'Application Details')

@section('content_header')
    <h1>Application Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Application #{{ $application->id }}</h3>
            <a href="{{ route('dashboard.provider.job-applications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Job Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Job Title</th>
                            <td>{{ $application->job->title_ar ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Specialization</th>
                            <td>{{ $application->job->specialization->name_ar ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Applicant Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Full Name</th>
                            <td>{{ $application->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $application->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $application->phone }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $application->current_location ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
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
                        </tr>
                        <tr>
                            <th>Applied At</th>
                            <td>{{ $application->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($application->cover_letter)
                <div class="mt-3">
                    <h5>Cover Letter</h5>
                    <div class="card">
                        <div class="card-body">
                            <p>{{ $application->cover_letter }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($application->extra_info)
                <div class="mt-3">
                    <h5>Additional Information</h5>
                    <div class="card">
                        <div class="card-body">
                            <p>{{ $application->extra_info }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($application->cv_file)
                <div class="mt-3">
                    <h5>CV File</h5>
                    <a href="{{ $application->cv_url }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download CV
                    </a>
                </div>
            @endif

            @if($application->notes)
                <div class="mt-3">
                    <h5>Notes</h5>
                    <div class="card">
                        <div class="card-body">
                            <p>{{ $application->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <hr>

            <h5>Update Status</h5>
            <form action="{{ route('dashboard.provider.job-applications.update-status', $application->id) }}" method="POST" class="mt-3">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-control" required>
                            <option value="new" {{ $application->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="notes" class="form-control" placeholder="Add notes..." value="{{ $application->notes }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop


