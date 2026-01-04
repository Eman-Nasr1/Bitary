@extends('adminlte::page')

@section('title', 'Create Job Specialization')

@section('content_header')
    <h1>Create Job Specialization</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Specialization Information</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.admin.job-specializations.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name (Arabic) *</label>
                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                value="{{ old('name_ar') }}" required>
                            @error('name_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name (English) *</label>
                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                value="{{ old('name_en') }}" required>
                            @error('name_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                        value="{{ old('slug') }}" placeholder="Auto-generated if left empty">
                    <small class="form-text text-muted">Leave empty to auto-generate from English name</small>
                    @error('slug')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="icheck-primary">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Specialization
                    </button>
                    <a href="{{ route('dashboard.admin.job-specializations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop


