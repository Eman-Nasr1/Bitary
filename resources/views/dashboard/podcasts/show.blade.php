@extends('adminlte::page')

@section('title', 'Podcast Details')

@section('content_header')
    <h1>Podcast Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ $podcast->title_ar }}</h3>
            <div>
                <a href="{{ route('dashboard.podcasts.edit', $podcast->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.podcasts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($podcast->cover_image_url)
                        <img src="{{ $podcast->cover_image_url }}" alt="Cover" class="img-fluid img-thumbnail">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Title (Arabic)</th>
                            <td>{{ $podcast->title_ar }}</td>
                        </tr>
                        <tr>
                            <th>Title (English)</th>
                            <td>{{ $podcast->title_en ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Description (Arabic)</th>
                            <td>{{ $podcast->description_ar }}</td>
                        </tr>
                        <tr>
                            <th>Description (English)</th>
                            <td>{{ $podcast->description_en ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><span class="badge badge-info">{{ ucfirst($podcast->podcast_type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $podcast->status == 'published' ? 'success' : ($podcast->status == 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($podcast->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Categories</th>
                            <td>
                                @forelse($podcast->categories as $category)
                                    <span class="badge badge-secondary">{{ $category->name_ar }}</span>
                                @empty
                                    <span class="text-muted">No categories</span>
                                @endforelse
                            </td>
                        </tr>
                        @if($podcast->youtube_channel_url)
                        <tr>
                            <th>YouTube Channel</th>
                            <td><a href="{{ $podcast->youtube_channel_url }}" target="_blank">{{ $podcast->youtube_channel_url }}</a></td>
                        </tr>
                        @endif
                        @if($podcast->spotify_url)
                        <tr>
                            <th>Spotify</th>
                            <td><a href="{{ $podcast->spotify_url }}" target="_blank">{{ $podcast->spotify_url }}</a></td>
                        </tr>
                        @endif
                        @if($podcast->apple_podcasts_url)
                        <tr>
                            <th>Apple Podcasts</th>
                            <td><a href="{{ $podcast->apple_podcasts_url }}" target="_blank">{{ $podcast->apple_podcasts_url }}</a></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Episodes</h4>
                <a href="{{ route('dashboard.episodes.create', ['podcast_id' => $podcast->id]) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Episode
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Instructor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($podcast->episodes as $episode)
                            <tr>
                                <td>{{ $episode->id }}</td>
                                <td>{{ $episode->title_ar }} @if($episode->title_en) <small class="text-muted">({{ $episode->title_en }})</small> @endif</td>
                                <td>{{ $episode->instructor ? $episode->instructor->name_ar : '-' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($episode->episode_type) }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $episode->status == 'published' ? 'success' : ($episode->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($episode->status) }}
                                    </span>
                                </td>
                                <td>{{ $episode->published_at ? $episode->published_at->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <a href="{{ route('dashboard.episodes.edit', $episode->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.episodes.destroy', $episode->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No episodes yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

