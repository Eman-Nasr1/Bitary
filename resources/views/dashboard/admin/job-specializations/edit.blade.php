@extends('adminlte::page')

@section('title', 'Edit Job Specialization')

@section('content_header')
    <h1>Edit Job Specialization</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Specialization Information</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.admin.job-specializations.update', $specialization->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name (Arabic) *</label>
                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                value="{{ old('name_ar', $specialization->name_ar) }}" required>
                            @error('name_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name (English) *</label>
                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                value="{{ old('name_en', $specialization->name_en) }}" required>
                            @error('name_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                        value="{{ old('slug', $specialization->slug) }}">
                    @error('slug')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="icheck-primary">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $specialization->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Specialization
                    </button>
                    <a href="{{ route('dashboard.admin.job-specializations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop


