@extends('adminlte::page')

@section('title', __('Podcasts'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Podcasts') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Podcasts') }}</h3>
            <a href="{{ route('dashboard.podcasts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.podcasts.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.podcasts.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Podcasts Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Cover</th>
                            <th style="width: 80px;">ID</th>
                            <th>Title (AR)</th>
                            <th>Title (EN)</th>
                            <th>Type</th>
                            <th>Categories</th>
                            <th>Status</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($podcasts as $podcast)
                            <tr>
                                <td>
                                    @if($podcast->cover_image_url)
                                        <img src="{{ $podcast->cover_image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $podcast->title_ar }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $podcast->id }}</td>
                                <td>{{ $podcast->title_ar }}</td>
                                <td>{{ $podcast->title_en ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($podcast->podcast_type) }}</span>
                                </td>
                                <td>
                                    @forelse($podcast->categories as $category)
                                        <span class="badge badge-secondary">{{ $category->name_ar }}</span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge badge-{{ $podcast->status == 'published' ? 'success' : ($podcast->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($podcast->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.podcasts.show', $podcast->id) }}" 
                                        class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.podcasts.edit', $podcast->id) }}" 
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.podcasts.destroy', $podcast->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this podcast?');">
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
                                <td colspan="8" class="text-center">No podcasts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $podcasts->links() }}
            </div>
        </div>
    </div>
@stop

