@extends('adminlte::page')

@section('title', 'View News')

@section('content_header')
    <h1>View News</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ $news->title_ar }}</h3>
            <div>
                <a href="{{ route('dashboard.news.edit', $news->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.news.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($news->cover_image_url)
                <div class="text-center mb-4">
                    <img src="{{ $news->cover_image_url }}" alt="Cover" class="img-fluid" style="max-height: 400px;">
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Title (AR):</strong> {{ $news->title_ar }}
                </div>
                @if($news->title_en)
                    <div class="col-md-6">
                        <strong>Title (EN):</strong> {{ $news->title_en }}
                    </div>
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Summary (AR):</strong>
                    <p>{{ $news->summary_ar }}</p>
                </div>
                @if($news->summary_en)
                    <div class="col-md-6">
                        <strong>Summary (EN):</strong>
                        <p>{{ $news->summary_en }}</p>
                    </div>
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Content (AR):</strong>
                    <div class="border p-3">
                        {!! $news->content_ar !!}
                    </div>
                </div>
                @if($news->content_en)
                    <div class="col-md-6">
                        <strong>Content (EN):</strong>
                        <div class="border p-3">
                            {!! $news->content_en !!}
                        </div>
                    </div>
                @endif
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <strong>Category:</strong>
                    <span class="badge badge-info">
                        {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $news->status == 'published' ? 'success' : ($news->status == 'draft' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($news->status) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Published At:</strong>
                    {{ $news->published_at ? $news->published_at->format('Y-m-d H:i') : 'Not published' }}
                </div>
                <div class="col-md-3">
                    <strong>Created At:</strong>
                    {{ $news->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            @if($news->tags && count($news->tags) > 0)
                <div class="mt-3">
                    <strong>Tags:</strong>
                    @foreach($news->tags as $tag)
                        <span class="badge badge-secondary mr-1">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif

            @if($news->author_name)
                <div class="mt-3">
                    <strong>Author:</strong> {{ $news->author_name }}
                </div>
            @endif

            <hr>

            <h4>Approved Comments ({{ $news->approvedComments->count() }})</h4>
            @if($news->approvedComments->count() > 0)
                <div class="mt-3">
                    @foreach($news->approvedComments as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $comment->user_name ?? ($comment->user->name ?? 'Anonymous') }}</strong>
                                        <small class="text-muted ml-2">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No approved comments yet.</p>
            @endif

            <div class="mt-3">
                <a href="{{ route('dashboard.news-comments.index', ['news_id' => $news->id]) }}" class="btn btn-info">
                    <i class="fas fa-comments"></i> Manage All Comments
                </a>
            </div>
        </div>
    </div>
@stop

