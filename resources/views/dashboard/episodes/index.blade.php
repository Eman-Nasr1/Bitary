@extends('adminlte::page')

@section('title', 'Episodes')

@section('content_header')
    <h1>Episodes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Episodes</h3>
            <a href="{{ route('dashboard.episodes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Episode
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.episodes.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="podcast_id" class="form-control">
                            <option value="">All Podcasts</option>
                            @foreach($podcasts as $podcast)
                                <option value="{{ $podcast->id }}" {{ request('podcast_id') == $podcast->id ? 'selected' : '' }}>
                                    {{ $podcast->title_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Episodes Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Title</th>
                            <th>Podcast</th>
                            <th>Instructor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($episodes as $episode)
                            <tr>
                                <td>{{ $episode->id }}</td>
                                <td>{{ $episode->title_ar }} @if($episode->title_en) <small class="text-muted">({{ $episode->title_en }})</small> @endif</td>
                                <td>
                                    <a href="{{ route('dashboard.podcasts.show', $episode->podcast_id) }}">
                                        {{ $episode->podcast->title_ar }}
                                    </a>
                                </td>
                                <td>{{ $episode->instructor ? $episode->instructor->name_ar : '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($episode->episode_type) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $episode->status == 'published' ? 'success' : ($episode->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($episode->status) }}
                                    </span>
                                </td>
                                <td>{{ $episode->published_at ? $episode->published_at->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <a href="{{ route('dashboard.episodes.edit', $episode->id) }}" 
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.episodes.destroy', $episode->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this episode?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No episodes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $episodes->links() }}
            </div>
        </div>
    </div>
@stop

