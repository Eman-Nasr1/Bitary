@extends('adminlte::page')

@section('title', 'Edit News Category')

@section('content_header')
    <h1>Edit News Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Category</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.news-categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="slug" class="form-control"
                        value="{{ old('slug', $category->slug) }}" required>
                    @error('slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Use only letters, numbers, dashes, and underscores.</small>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Active</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('dashboard.news-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
@stop

