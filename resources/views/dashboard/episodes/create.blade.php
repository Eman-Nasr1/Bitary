@extends('adminlte::page')

@section('title', 'Create Episode')

@section('content_header')
    <h1>Create Episode</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>New Episode</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.episodes.store') }}" method="POST" enctype="multipart/form-data" id="episodeForm">
                @csrf

                <div class="form-group">
                    <label for="podcast_id">Podcast <span class="text-danger">*</span></label>
                    <select name="podcast_id" id="podcast_id" class="form-control" required>
                        <option value="">Select Podcast</option>
                        @foreach($podcasts as $podcast)
                            <option value="{{ $podcast->id }}" {{ old('podcast_id', $podcastId) == $podcast->id ? 'selected' : '' }}>
                                {{ $podcast->title_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('podcast_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="instructor_id">Instructor (محاضر)</label>
                    <select name="instructor_id" id="instructor_id" class="form-control">
                        <option value="">Select Instructor</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name_ar }} @if($instructor->name_en) ({{ $instructor->name_en }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

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
                            <label for="description_ar">Description (Arabic) <span class="text-danger">*</span></label>
                            <textarea name="description_ar" id="description_ar" class="form-control" rows="5" required>{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_en">Description (English)</label>
                            <textarea name="description_en" id="description_en" class="form-control" rows="5">{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="thumbnail_image">Thumbnail Image</label>
                    <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control" accept="image/*">
                    @error('thumbnail_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Max size: 2MB. Allowed types: jpeg, png, jpg, gif</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="episode_type">Episode Type <span class="text-danger">*</span></label>
                            <select name="episode_type" id="episode_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="video" {{ old('episode_type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="audio" {{ old('episode_type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            </select>
                            @error('episode_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="published_at">Published At</label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" 
                        value="{{ old('published_at') }}">
                    @error('published_at')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Video Fields --}}
                <div id="videoFields" style="display: none;">
                    <div class="form-group">
                        <label for="youtube_url">YouTube URL <span class="text-danger">*</span></label>
                        <input type="url" name="youtube_url" id="youtube_url" class="form-control" 
                            value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Audio Fields --}}
                <div id="audioFields" style="display: none;">
                    <div class="form-group">
                        <label for="audio_file">Audio File</label>
                        <input type="file" name="audio_file" id="audio_file" class="form-control" accept="audio/mp3,audio/wav">
                        @error('audio_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Max size: 50MB. Allowed types: mp3, wav</small>
                    </div>
                    <div class="form-group">
                        <label for="spotify_url">Spotify URL</label>
                        <input type="url" name="spotify_url" id="spotify_url" class="form-control" 
                            value="{{ old('spotify_url') }}" placeholder="https://open.spotify.com/...">
                        @error('spotify_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Required if audio file is not uploaded</small>
                    </div>
                    <div class="form-group">
                        <label for="apple_podcasts_url">Apple Podcasts URL</label>
                        <input type="url" name="apple_podcasts_url" id="apple_podcasts_url" class="form-control" 
                            value="{{ old('apple_podcasts_url') }}" placeholder="https://podcasts.apple.com/...">
                        @error('apple_podcasts_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Required if audio file is not uploaded</small>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Episode
                    </button>
                    <a href="{{ $podcastId ? route('dashboard.podcasts.show', $podcastId) : route('dashboard.episodes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('js')
    <script>
        document.getElementById('episode_type').addEventListener('change', function() {
            const episodeType = this.value;
            const videoFields = document.getElementById('videoFields');
            const audioFields = document.getElementById('audioFields');
            
            if (episodeType === 'video') {
                videoFields.style.display = 'block';
                audioFields.style.display = 'none';
                document.getElementById('youtube_url').required = true;
                document.getElementById('audio_file').required = false;
            } else if (episodeType === 'audio') {
                videoFields.style.display = 'none';
                audioFields.style.display = 'block';
                document.getElementById('youtube_url').required = false;
            } else {
                videoFields.style.display = 'none';
                audioFields.style.display = 'none';
                document.getElementById('youtube_url').required = false;
                document.getElementById('audio_file').required = false;
            }
        });

        // Trigger on page load if value exists
        if (document.getElementById('episode_type').value) {
            document.getElementById('episode_type').dispatchEvent(new Event('change'));
        }
    </script>
    @endpush
@stop

