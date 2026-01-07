@extends('adminlte::page')

@section('title', 'Edit Job')

@section('content_header')
    <h1>Edit Job</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Job Information</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.admin.jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Provider *</label>
                            <select name="provider_id" class="form-control @error('provider_id') is-invalid @enderror" required>
                                <option value="">Select Provider</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ old('provider_id', $job->provider_id) == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }} ({{ $provider->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('provider_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Specialization *</label>
                            <select name="specialization_id" class="form-control @error('specialization_id') is-invalid @enderror" required>
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec->id }}" {{ old('specialization_id', $job->specialization_id) == $spec->id ? 'selected' : '' }}>
                                        {{ $spec->name_ar }} / {{ $spec->name_en }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialization_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Location (City)</label>
                            <select name="city_id" class="form-control @error('city_id') is-invalid @enderror">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $job->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title (Arabic) *</label>
                            <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror" 
                                value="{{ old('title_ar', $job->title_ar) }}" required>
                            @error('title_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title (English) *</label>
                            <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" 
                                value="{{ old('title_en', $job->title_en) }}" required>
                            @error('title_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control @error('job_type') is-invalid @enderror">
                        <option value="">Select Job Type</option>
                        <option value="full_time" {{ old('job_type', $job->job_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('job_type', $job->job_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                    </select>
                    @error('job_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Job Image</label>
                    @if($job->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $job->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            <p class="text-muted small">Current Image</p>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB. Leave empty to keep current image.</small>
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description (Arabic)</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3">{{ old('description_ar', $job->description_ar) }}</textarea>
                            @error('description_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description (English)</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en', $job->description_en) }}</textarea>
                            @error('description_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Responsibilities (Arabic)</label>
                            <textarea name="responsibilities_ar" class="form-control @error('responsibilities_ar') is-invalid @enderror" rows="3">{{ old('responsibilities_ar', $job->responsibilities_ar) }}</textarea>
                            @error('responsibilities_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Responsibilities (English)</label>
                            <textarea name="responsibilities_en" class="form-control @error('responsibilities_en') is-invalid @enderror" rows="3">{{ old('responsibilities_en', $job->responsibilities_en) }}</textarea>
                            @error('responsibilities_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Qualifications (Arabic)</label>
                            <textarea name="qualifications_ar" class="form-control @error('qualifications_ar') is-invalid @enderror" rows="3">{{ old('qualifications_ar', $job->qualifications_ar) }}</textarea>
                            @error('qualifications_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Qualifications (English)</label>
                            <textarea name="qualifications_en" class="form-control @error('qualifications_en') is-invalid @enderror" rows="3">{{ old('qualifications_en', $job->qualifications_en) }}</textarea>
                            @error('qualifications_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apply Method *</label>
                            <select name="apply_method" id="apply_method" class="form-control @error('apply_method') is-invalid @enderror" required>
                                <option value="in_app" {{ old('apply_method', $job->apply_method) == 'in_app' ? 'selected' : '' }}>In App</option>
                                <option value="whatsapp" {{ old('apply_method', $job->apply_method) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="email" {{ old('apply_method', $job->apply_method) == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="external_link" {{ old('apply_method', $job->apply_method) == 'external_link' ? 'selected' : '' }}>External Link</option>
                            </select>
                            @error('apply_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status', $job->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="rejected" {{ old('status', $job->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="archived" {{ old('status', $job->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Dynamic fields based on apply_method --}}
                <div id="whatsapp_field" style="display: none;">
                    <div class="form-group">
                        <label>WhatsApp Number *</label>
                        <input type="text" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                            value="{{ old('whatsapp_number', $job->whatsapp_number) }}">
                        @error('whatsapp_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="email_field" style="display: none;">
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email_address" class="form-control @error('email_address') is-invalid @enderror" 
                            value="{{ old('email_address', $job->email_address) }}">
                        @error('email_address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="external_link_field" style="display: none;">
                    <div class="form-group">
                        <label>External Link *</label>
                        <input type="url" name="external_link" class="form-control @error('external_link') is-invalid @enderror" 
                            value="{{ old('external_link', $job->external_link) }}">
                        @error('external_link')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if($job->status == 'rejected')
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="3">{{ old('rejection_reason', $job->rejection_reason) }}</textarea>
                        @error('rejection_reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Job
                    </button>
                    <a href="{{ route('dashboard.admin.jobs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('js')
    <script>
        document.getElementById('apply_method').addEventListener('change', function() {
            const method = this.value;
            document.getElementById('whatsapp_field').style.display = method === 'whatsapp' ? 'block' : 'none';
            document.getElementById('email_field').style.display = method === 'email' ? 'block' : 'none';
            document.getElementById('external_link_field').style.display = method === 'external_link' ? 'block' : 'none';
        });
        
        // Trigger on page load
        document.getElementById('apply_method').dispatchEvent(new Event('change'));
    </script>
    @endpush
@stop


