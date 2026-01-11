@extends('adminlte::page')

@section('title', 'View Static Page')

@section('content_header')
    <h1>View Static Page</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ $page->title_ar }}</h3>
            <div>
                <a href="{{ route('dashboard.static-pages.edit', $page->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.static-pages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Title (AR):</strong> {{ $page->title_ar }}
                </div>
                @if($page->title_en)
                    <div class="col-md-6">
                        <strong>Title (EN):</strong> {{ $page->title_en }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <strong>Slug:</strong> <code>{{ $page->slug }}</code>
            </div>

            @if($page->description_ar || $page->description_en)
                <div class="row mb-3">
                    @if($page->description_ar)
                        <div class="col-md-6">
                            <strong>Description (AR):</strong>
                            <p>{{ $page->description_ar }}</p>
                        </div>
                    @endif
                    @if($page->description_en)
                        <div class="col-md-6">
                            <strong>Description (EN):</strong>
                            <p>{{ $page->description_en }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Content (AR):</strong>
                    <div class="border p-3 mt-2">
                        {!! $page->content_ar !!}
                    </div>
                </div>
                @if($page->content_en)
                    <div class="col-md-6">
                        <strong>Content (EN):</strong>
                        <div class="border p-3 mt-2">
                            {!! $page->content_en !!}
                        </div>
                    </div>
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $page->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($page->status) }}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Created At:</strong>
                    {{ $page->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="mb-3">
                <strong>Updated At:</strong>
                {{ $page->updated_at->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>
@stop

