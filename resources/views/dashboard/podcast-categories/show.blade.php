@extends('adminlte::page')

@section('title', 'Podcast Category Details')

@section('content_header')
    <h1>Podcast Category Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ $category->name_ar }}</h3>
            <div>
                <a href="{{ route('dashboard.podcast-categories.edit', $category->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.podcast-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Name (Arabic)</th>
                    <td>{{ $category->name_ar }}</td>
                </tr>
                <tr>
                    <th>Name (English)</th>
                    <td>{{ $category->name_en ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Podcasts Count</th>
                    <td>
                        <span class="badge badge-info">{{ $category->podcasts->count() }}</span>
                    </td>
                </tr>
            </table>

            <hr>

            <h4>Podcasts in this Category</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title (AR)</th>
                            <th>Title (EN)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($category->podcasts as $podcast)
                            <tr>
                                <td>{{ $podcast->id }}</td>
                                <td>{{ $podcast->title_ar }}</td>
                                <td>{{ $podcast->title_en ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $podcast->status == 'published' ? 'success' : ($podcast->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($podcast->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.podcasts.show', $podcast->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No podcasts in this category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

