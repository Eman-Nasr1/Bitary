@extends('adminlte::page')

@section('title', 'Edit Specialization')

@section('content_header')
    <h1>Edit Specialization</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Specialization</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.specializations.update', $specialization->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_ar">Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control" 
                                value="{{ old('name_ar', $specialization->name_ar) }}" required>
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_en">Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" id="name_en" class="form-control" 
                                value="{{ old('name_en', $specialization->name_en) }}" required>
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_ar">Description (Arabic)</label>
                            <textarea name="description_ar" id="description_ar" class="form-control" rows="4">{{ old('description_ar', $specialization->description_ar) }}</textarea>
                            @error('description_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_en">Description (English)</label>
                            <textarea name="description_en" id="description_en" class="form-control" rows="4">{{ old('description_en', $specialization->description_en) }}</textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Specialization
                    </button>
                    <a href="{{ route('dashboard.specializations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

