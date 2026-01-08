@extends('adminlte::page')

@section('title', 'Edit Podcast')

@section('content_header')
    <h1>Edit Podcast</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Podcast</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.podcasts.update', $podcast->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_ar">Title (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" 
                                value="{{ old('title_ar', $podcast->title_ar) }}" required>
                            @error('title_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_en">Title (English)</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" 
                                value="{{ old('title_en', $podcast->title_en) }}">
                            @error('title_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_ar">Description (Arabic) <span class="text-danger">*</span></label>
                            <textarea name="description_ar" id="description_ar" class="form-control" rows="5" required>{{ old('description_ar', $podcast->description_ar) }}</textarea>
                            @error('description_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_en">Description (English)</label>
                            <textarea name="description_en" id="description_en" class="form-control" rows="5">{{ old('description_en', $podcast->description_en) }}</textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cover_image">Cover Image</label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    @if($podcast->cover_image_url)
                        <div class="mt-2">
                            <img src="{{ $podcast->cover_image_url }}" alt="Current cover" style="max-width: 200px;" class="img-thumbnail">
                            <p class="text-muted mt-1">Current cover image</p>
                        </div>
                    @endif
                    @error('cover_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Max size: 2MB. Allowed types: jpeg, png, jpg, gif</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="podcast_type">Podcast Type <span class="text-danger">*</span></label>
                            <select name="podcast_type" id="podcast_type" class="form-control" required>
                                <option value="video" {{ old('podcast_type', $podcast->podcast_type) == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="audio" {{ old('podcast_type', $podcast->podcast_type) == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="both" {{ old('podcast_type', $podcast->podcast_type) == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                            @error('podcast_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="draft" {{ old('status', $podcast->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $podcast->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $podcast->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="categories">Categories</label>
                    <select name="categories[]" id="categories" class="form-control select2" multiple style="width: 100%;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $podcast->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name_ar }} @if($category->name_en) ({{ $category->name_en }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Select one or more categories. You can search by typing.</small>
                </div>

                <div class="form-group">
                    <label for="youtube_channel_url">YouTube Channel URL</label>
                    <input type="url" name="youtube_channel_url" id="youtube_channel_url" class="form-control" 
                        value="{{ old('youtube_channel_url', $podcast->youtube_channel_url) }}" placeholder="https://www.youtube.com/channel/...">
                    @error('youtube_channel_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="spotify_url">Spotify URL</label>
                    <input type="url" name="spotify_url" id="spotify_url" class="form-control" 
                        value="{{ old('spotify_url', $podcast->spotify_url) }}" placeholder="https://open.spotify.com/...">
                    @error('spotify_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="apple_podcasts_url">Apple Podcasts URL</label>
                    <input type="url" name="apple_podcasts_url" id="apple_podcasts_url" class="form-control" 
                        value="{{ old('apple_podcasts_url', $podcast->apple_podcasts_url) }}" placeholder="https://podcasts.apple.com/...">
                    @error('apple_podcasts_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Podcast
                    </button>
                    <a href="{{ route('dashboard.podcasts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
<script>
    $(document).ready(function() {
        $('#categories').select2({
            placeholder: 'Select categories...',
            allowClear: true
        });
    });
</script>
@endpush

