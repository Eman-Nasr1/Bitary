@extends('adminlte::page')

@section('title', 'Create News')

@section('content_header')
    <h1>Create News</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>New News</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_ar">Title (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" 
                                value="{{ old('title_ar') }}" required>
                            @error('title_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_en">Title (English)</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" 
                                value="{{ old('title_en') }}">
                            @error('title_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="summary_ar">Summary (Arabic) <span class="text-danger">*</span></label>
                            <textarea name="summary_ar" id="summary_ar" class="form-control" rows="3" required>{{ old('summary_ar') }}</textarea>
                            @error('summary_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="summary_en">Summary (English)</label>
                            <textarea name="summary_en" id="summary_en" class="form-control" rows="3">{{ old('summary_en') }}</textarea>
                            @error('summary_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content_ar">Content (Arabic) <span class="text-danger">*</span></label>
                            <textarea name="content_ar" id="content_ar" class="form-control summernote" rows="10" required>{{ old('content_ar') }}</textarea>
                            @error('content_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content_en">Content (English)</label>
                            <textarea name="content_en" id="content_en" class="form-control summernote" rows="10">{{ old('content_en') }}</textarea>
                            @error('content_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cover_image">Cover Image</label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    @error('cover_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Max size: 2MB. Allowed types: jpeg, png, jpg, gif</small>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="animal_health" {{ old('category') == 'animal_health' ? 'selected' : '' }}>Animal Health</option>
                                <option value="veterinary_medicine" {{ old('category') == 'veterinary_medicine' ? 'selected' : '' }}>Veterinary Medicine</option>
                                <option value="market_trends" {{ old('category') == 'market_trends' ? 'selected' : '' }}>Market Trends</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="published_at">Published At</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" 
                                value="{{ old('published_at') }}">
                            @error('published_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select name="tags[]" id="tags" class="form-control select2" multiple style="width: 100%;" data-tags="true">
                        @if(old('tags'))
                            @foreach(old('tags') as $tag)
                                <option value="{{ $tag }}" selected>{{ $tag }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('tags')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Type and press Enter to add tags</small>
                </div>

                <div class="form-group">
                    <label for="author_name">Author Name</label>
                    <input type="text" name="author_name" id="author_name" class="form-control" 
                        value="{{ old('author_name') }}">
                    @error('author_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create News
                    </button>
                    <a href="{{ route('dashboard.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Summernote WYSIWYG editor
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Initialize Select2 for tags with tags enabled
        $('#tags').select2({
            tags: true,
            placeholder: 'Type and press Enter to add tags',
            allowClear: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>
@endpush

