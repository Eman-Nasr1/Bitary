@extends('adminlte::page')

@section('title', 'Create Instructor')

@section('content_header')
    <h1>Create Instructor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>New Instructor</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.instructors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_ar">Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control" 
                                value="{{ old('name_ar') }}" required>
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_en">Name (English)</label>
                            <input type="text" name="name_en" id="name_en" class="form-control" 
                                value="{{ old('name_en') }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" 
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" 
                                value="{{ old('phone') }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bio_ar">Bio (Arabic)</label>
                            <textarea name="bio_ar" id="bio_ar" class="form-control" rows="3">{{ old('bio_ar') }}</textarea>
                            @error('bio_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bio_en">Bio (English)</label>
                            <textarea name="bio_en" id="bio_en" class="form-control" rows="3">{{ old('bio_en') }}</textarea>
                            @error('bio_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Max size: 2MB. Allowed types: jpeg, png, jpg, gif</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Instructor
                    </button>
                    <a href="{{ route('dashboard.instructors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

