@extends('adminlte::page')

@section('title', 'Edit Podcast Category')

@section('content_header')
    <h1>Edit Podcast Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Podcast Category</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.podcast-categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_ar">Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control" 
                                value="{{ old('name_ar', $category->name_ar) }}" required>
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_en">Name (English)</label>
                            <input type="text" name="name_en" id="name_en" class="form-control" 
                                value="{{ old('name_en', $category->name_en) }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                    <a href="{{ route('dashboard.podcast-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

