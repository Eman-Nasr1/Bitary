@extends('adminlte::page')

@section('title', 'Course Details')

@section('content_header')
    <h1>Course Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Course #{{ $course->id }}</h3>
            <a href="{{ route('dashboard.courses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            @if($course->image_url)
                <div class="mb-4 text-center">
                    <img src="{{ $course->image_url }}" 
                        alt="{{ $course->title_en }}" 
                        class="img-fluid rounded"
                        style="max-width: 100%; max-height: 400px; object-fit: cover;">
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Basic Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Title (English):</th>
                            <td><strong>{{ $course->title_en }}</strong></td>
                        </tr>
                        <tr>
                            <th>Title (Arabic):</th>
                            <td><strong>{{ $course->title_ar }}</strong></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td><span class="badge badge-info">{{ ucfirst($course->category) }}</span></td>
                        </tr>
                        <tr>
                            <th>Level:</th>
                            <td><span class="badge badge-secondary">{{ ucfirst($course->level) }}</span></td>
                        </tr>
                        <tr>
                            <th>Language:</th>
                            <td><span class="badge badge-primary">{{ strtoupper($course->language) }}</span></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($course->status == 'published')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Published</span>
                                @elseif($course->status == 'draft')
                                    <span class="badge badge-warning"><i class="fas fa-edit"></i> Draft</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-archive"></i> Archived</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Pricing Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Is Free:</th>
                            <td>
                                @if($course->is_free)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                        </tr>
                        @if(!$course->is_free)
                            <tr>
                                <th>Price:</th>
                                <td><strong>{{ $course->price }} {{ $course->currency }}</strong></td>
                            </tr>
                            @if($course->discount_percent)
                                <tr>
                                    <th>Discount:</th>
                                    <td><span class="text-success">-{{ $course->discount_percent }}%</span></td>
                                </tr>
                            @endif
                        @endif
                        <tr>
                            <th>Payment Method:</th>
                            <td><span class="badge badge-info">{{ ucfirst($course->payment_method) }}</span></td>
                        </tr>
                        @if($course->payment_method == 'whatsapp' && $course->whatsapp_join_link)
                            <tr>
                                <th>WhatsApp Link:</th>
                                <td><a href="{{ $course->whatsapp_join_link }}" target="_blank">{{ $course->whatsapp_join_link }}</a></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Overview</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>English:</h6>
                    <p>{{ $course->overview_en ?? '—' }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Arabic:</h6>
                    <p>{{ $course->overview_ar ?? '—' }}</p>
                </div>
            </div>

            @if($course->description_en || $course->description_ar)
                <hr>
                <h5 class="mb-3">Description</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6>English:</h6>
                        <p style="white-space: pre-wrap;">{{ $course->description_en ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Arabic:</h6>
                        <p style="white-space: pre-wrap;">{{ $course->description_ar ?? '—' }}</p>
                    </div>
                </div>
            @endif

            <hr>

            <h5 class="mb-3">Duration</h5>
            <div class="row">
                <div class="col-md-4">
                    <strong>Duration:</strong> {{ $course->duration_weeks ?? '—' }} weeks
                </div>
                <div class="col-md-4">
                    <strong>Hours Per Week:</strong> {{ $course->hours_per_week ?? '—' }}
                </div>
                <div class="col-md-4">
                    <strong>Days Per Week:</strong> {{ $course->days_per_week ?? '—' }}
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Certificate</h5>
            <table class="table table-bordered">
                <tr>
                    <th width="20%">Available:</th>
                    <td>
                        @if($course->certificate_available)
                            <span class="badge badge-success">Yes</span>
                            @if($course->certificate_type)
                                <span class="badge badge-primary ml-2">{{ ucfirst($course->certificate_type) }}</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </td>
                </tr>
            </table>

            <hr>

            <h5 class="mb-3">Instructors</h5>
            @if($course->instructors->count() > 0)
                <div class="row">
                    @foreach($course->instructors as $instructor)
                        <div class="col-md-3 mb-2">
                            <span class="badge badge-primary badge-lg">
                                {{ $instructor->name_ar }}
                                @if($instructor->name_en)
                                    ({{ $instructor->name_en }})
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No instructors assigned.</p>
            @endif

            <hr>

            <h5 class="mb-3">Specializations</h5>
            @if($course->specializations->count() > 0)
                <div class="row">
                    @foreach($course->specializations as $specialization)
                        <div class="col-md-3 mb-2">
                            <span class="badge badge-info badge-lg">
                                {{ $specialization->name_en }} ({{ $specialization->name_ar }})
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No specializations assigned.</p>
            @endif

            @if($course->intro_video_url || $course->intro_video_iframe)
                <hr>
                <h5 class="mb-3">Introduction Video</h5>
                @if($course->intro_video_iframe)
                    <div class="embed-responsive embed-responsive-16by9">
                        {!! $course->intro_video_iframe !!}
                    </div>
                @elseif($course->intro_video_url)
                    <p><a href="{{ $course->intro_video_url }}" target="_blank">{{ $course->intro_video_url }}</a></p>
                @endif
            @endif

            <div class="mt-4">
                <a href="{{ route('dashboard.courses.edit', $course->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Course
                </a>
            </div>
        </div>
    </div>
@stop

