@extends('adminlte::page')

@section('title', 'Edit Course')

@section('content_header')
    <h1>Edit Course</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Course</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST" id="courseForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Basic Information --}}
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_en">Title (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_en" id="title_en" class="form-control" 
                                        value="{{ old('title_en', $course->title_en) }}" required>
                                    @error('title_en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_ar">Title (Arabic) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_ar" id="title_ar" class="form-control" 
                                        value="{{ old('title_ar', $course->title_ar) }}" required>
                                    @error('title_ar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">Course Image</label>
                            @if($course->image_url)
                                <div class="mb-2">
                                    <img src="{{ $course->image_url }}" 
                                        alt="{{ $course->title_en }}" 
                                        style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 4px;">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Max size: 2MB. Allowed types: jpeg, png, jpg, gif</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Category <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="vets" {{ old('category', $course->category) == 'vets' ? 'selected' : '' }}>Vets</option>
                                        <option value="students" {{ old('category', $course->category) == 'students' ? 'selected' : '' }}>Students</option>
                                        <option value="breeders" {{ old('category', $course->category) == 'breeders' ? 'selected' : '' }}>Breeders</option>
                                    </select>
                                    @error('category')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="level">Level <span class="text-danger">*</span></label>
                                    <select name="level" id="level" class="form-control" required>
                                        <option value="">Select Level</option>
                                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                    @error('level')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="language">Language <span class="text-danger">*</span></label>
                                    <select name="language" id="language" class="form-control" required>
                                        <option value="">Select Language</option>
                                        <option value="ar" {{ old('language', $course->language) == 'ar' ? 'selected' : '' }}>Arabic</option>
                                        <option value="en" {{ old('language', $course->language) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="mixed" {{ old('language', $course->language) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                    </select>
                                    @error('language')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="overview_en">Overview (English)</label>
                                    <textarea name="overview_en" id="overview_en" class="form-control" rows="3">{{ old('overview_en', $course->overview_en) }}</textarea>
                                    @error('overview_en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="overview_ar">Overview (Arabic)</label>
                                    <textarea name="overview_ar" id="overview_ar" class="form-control" rows="3">{{ old('overview_ar', $course->overview_ar) }}</textarea>
                                    @error('overview_ar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en">Description (English)</label>
                                    <textarea name="description_en" id="description_en" class="form-control" rows="5">{{ old('description_en', $course->description_en) }}</textarea>
                                    @error('description_en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Detailed description of the course</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar">Description (Arabic)</label>
                                    <textarea name="description_ar" id="description_ar" class="form-control" rows="5">{{ old('description_ar', $course->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">وصف تفصيلي للكورس</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Specializations --}}
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5>Specializations</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="specializations">Select Specializations</label>
                            <select name="specializations[]" id="specializations" class="form-control select2" multiple>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" 
                                        {{ in_array($specialization->id, old('specializations', $course->specializations->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $specialization->name_en }} ({{ $specialization->name_ar }})
                                    </option>
                                @endforeach
                            </select>
                            @error('specializations')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Instructors --}}
                <div class="card mb-3">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5>Instructors</h5>
                        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#addInstructorModal">
                            <i class="fas fa-plus"></i> Add Instructor
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="instructors">Select Instructors</label>
                            <select name="instructors[]" id="instructors" class="form-control select2" multiple>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" 
                                        {{ in_array($instructor->id, old('instructors', $course->instructors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $instructor->name_ar }} @if($instructor->name_en)({{ $instructor->name_en }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('instructors')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Video --}}
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h5>Introduction Video</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="intro_video_url">YouTube URL</label>
                                    <input type="url" name="intro_video_url" id="intro_video_url" class="form-control" 
                                        value="{{ old('intro_video_url', $course->intro_video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                    @error('intro_video_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="intro_video_iframe">Video Iframe (Optional)</label>
                                    <textarea name="intro_video_iframe" id="intro_video_iframe" class="form-control" rows="2">{{ old('intro_video_iframe', $course->intro_video_iframe) }}</textarea>
                                    @error('intro_video_iframe')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Duration --}}
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5>Duration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duration_weeks">Duration (Weeks)</label>
                                    <input type="number" name="duration_weeks" id="duration_weeks" class="form-control" 
                                        value="{{ old('duration_weeks', $course->duration_weeks) }}" min="1">
                                    @error('duration_weeks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hours_per_week">Hours Per Week</label>
                                    <input type="number" name="hours_per_week" id="hours_per_week" class="form-control" 
                                        value="{{ old('hours_per_week', $course->hours_per_week) }}" min="1">
                                    @error('hours_per_week')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="days_per_week">Days Per Week</label>
                                    <input type="number" name="days_per_week" id="days_per_week" class="form-control" 
                                        value="{{ old('days_per_week', $course->days_per_week) }}" min="1" max="7">
                                    @error('days_per_week')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Certificate --}}
                <div class="card mb-3">
                    <div class="card-header bg-purple text-white">
                        <h5>Certificate</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="certificate_available" id="certificate_available" 
                                    class="custom-control-input" value="1" {{ old('certificate_available', $course->certificate_available) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="certificate_available">Certificate Available</label>
                            </div>
                        </div>
                        <div class="form-group" id="certificate_type_group" style="display: {{ old('certificate_available', $course->certificate_available) ? 'block' : 'none' }};">
                            <label for="certificate_type">Certificate Type <span class="text-danger">*</span></label>
                            <select name="certificate_type" id="certificate_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="digital" {{ old('certificate_type', $course->certificate_type) == 'digital' ? 'selected' : '' }}>Digital</option>
                                <option value="physical" {{ old('certificate_type', $course->certificate_type) == 'physical' ? 'selected' : '' }}>Physical</option>
                            </select>
                            @error('certificate_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <h5>Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_free" id="is_free" 
                                    class="custom-control-input" value="1" {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_free">Free Course</label>
                            </div>
                        </div>
                        <div id="pricing_fields" style="display: {{ old('is_free', $course->is_free) ? 'none' : 'block' }};">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="number" name="price" id="price" class="form-control" 
                                            value="{{ old('price', $course->price) }}" step="0.01" min="0">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency">Currency <span class="text-danger">*</span></label>
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="">Select Currency</option>
                                            <option value="EGP" {{ old('currency', $course->currency) == 'EGP' ? 'selected' : '' }}>EGP</option>
                                            <option value="USD" {{ old('currency', $course->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency', $course->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                        @error('currency')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discount_percent">Discount (%)</label>
                                        <input type="number" name="discount_percent" id="discount_percent" class="form-control" 
                                            value="{{ old('discount_percent', $course->discount_percent) }}" min="0" max="100" step="0.01">
                                        @error('discount_percent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        <h5>Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Select Method</option>
                                <option value="online" {{ old('payment_method', $course->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="whatsapp" {{ old('payment_method', $course->payment_method) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            </select>
                            @error('payment_method')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="whatsapp_link_group" style="display: {{ old('payment_method', $course->payment_method) == 'whatsapp' ? 'block' : 'none' }};">
                            <label for="whatsapp_join_link">WhatsApp Join Link <span class="text-danger">*</span></label>
                            <input type="url" name="whatsapp_join_link" id="whatsapp_join_link" class="form-control" 
                                value="{{ old('whatsapp_join_link', $course->whatsapp_join_link) }}" placeholder="https://chat.whatsapp.com/...">
                            @error('whatsapp_join_link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Course
                    </button>
                    <a href="{{ route('dashboard.courses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Instructor Modal --}}
    @include('dashboard.courses.modals.add-instructor')
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select options',
                allowClear: true
            });

            // Toggle certificate type field
            $('#certificate_available').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#certificate_type_group').show();
                    $('#certificate_type').prop('required', true);
                } else {
                    $('#certificate_type_group').hide();
                    $('#certificate_type').prop('required', false).val('');
                }
            });

            // Toggle pricing fields
            $('#is_free').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#pricing_fields').hide();
                    $('#price, #currency, #discount_percent').prop('required', false).val('');
                } else {
                    $('#pricing_fields').show();
                    $('#price, #currency').prop('required', true);
                }
            });

            // Toggle WhatsApp link field
            $('#payment_method').on('change', function() {
                if ($(this).val() === 'whatsapp') {
                    $('#whatsapp_link_group').show();
                    $('#whatsapp_join_link').prop('required', true);
                } else {
                    $('#whatsapp_link_group').hide();
                    $('#whatsapp_join_link').prop('required', false).val('');
                }
            });
        });
    </script>
@stop

