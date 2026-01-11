@extends('adminlte::page')

@section('title', __('Podcast Categories'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Podcast Categories') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Podcast Categories') }}</h3>
            <a href="{{ route('dashboard.podcast-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Search --}}
            <form action="{{ route('dashboard.podcast-categories.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.podcast-categories.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Categories Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name (Arabic)</th>
                            <th>Name (English)</th>
                            <th>Podcasts Count</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name_ar }}</td>
                                <td>{{ $category->name_en ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $category->podcasts->count() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.podcast-categories.show', $category->id) }}" 
                                        class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.podcast-categories.edit', $category->id) }}" 
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.podcast-categories.destroy', $category->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@stop

