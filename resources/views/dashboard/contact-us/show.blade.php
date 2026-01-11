@extends('adminlte::page')

@section('title', __('View Contact Message'))

@push('topnav_right')
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-language"></i> {{ __('Language') }}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="{{ url('/lang/ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'ar' ? 'text-success' : 'text-secondary' }}"></i>
                {{ __('Arabic') }}
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ url('/lang/en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'en' ? 'text-success' : 'text-secondary' }}"></i>
                {{ __('English') }}
            </a>
        </div>
    </li>
@endpush

@section('content_header')
    <h1><i class="fas fa-envelope-open"></i> {{ __('View Contact Message') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Message Details') }}</h3>
            <div>
                @if($message->status === 'new')
                    <form action="{{ route('dashboard.contact-us.mark-as-read', $message->id) }}" 
                        method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-check"></i> {{ __('Mark as Read') }}
                        </button>
                    </form>
                @endif
                @if($message->status !== 'archived')
                    <form action="{{ route('dashboard.contact-us.archive', $message->id) }}" 
                        method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-archive"></i> {{ __('Archive') }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('dashboard.contact-us.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Message Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>{{ __('Name') }}:</strong>
                    <p class="mb-0">{{ $message->name }}</p>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Email') }}:</strong>
                    <p class="mb-0">
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </p>
                </div>
            </div>

            @if($message->phone)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('Phone') }}:</strong>
                        <p class="mb-0">
                            <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                        </p>
                    </div>
                </div>
            @endif

            @if($message->subject)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>{{ __('Subject') }}:</strong>
                        <p class="mb-0">{{ $message->subject }}</p>
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-12">
                    <strong>{{ __('Message') }}:</strong>
                    <div class="border p-3 mt-2 bg-light">
                        <p class="mb-0">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Status & Dates --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>{{ __('Status') }}:</strong>
                    @if($message->status === 'new')
                        <span class="badge badge-warning">{{ __('New') }}</span>
                    @elseif($message->status === 'read')
                        <span class="badge badge-info">{{ __('Read') }}</span>
                    @elseif($message->status === 'replied')
                        <span class="badge badge-success">{{ __('Replied') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('Archived') }}</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Created At') }}:</strong>
                    <p class="mb-0">{{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
                @if($message->read_at)
                    <div class="col-md-3">
                        <strong>{{ __('Read At') }}:</strong>
                        <p class="mb-0">{{ $message->read_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                @endif
                @if($message->replied_at)
                    <div class="col-md-3">
                        <strong>{{ __('Replied At') }}:</strong>
                        <p class="mb-0">{{ $message->replied_at->format('Y-m-d H:i:s') }}</p>
                        @if($message->repliedByAdmin)
                            <small class="text-muted">{{ __('Replied by') }} {{ $message->repliedByAdmin->name }}</small>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Admin Reply Section --}}
            @if($message->admin_reply)
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>{{ __('Admin Reply') }}:</strong>
                        <div class="border p-3 mt-2 bg-info text-white">
                            <p class="mb-0">{{ $message->admin_reply }}</p>
                        </div>
                        @if($message->repliedByAdmin)
                            <small class="text-muted">
                                {{ __('Replied by') }}: {{ $message->repliedByAdmin->name }} 
                                {{ __('on') }} {{ $message->replied_at->format('Y-m-d H:i:s') }}
                            </small>
                        @endif
                    </div>
                </div>
            @else
                {{-- Reply Form --}}
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4>{{ __('Reply to Message') }}</h4>
                        <form action="{{ route('dashboard.contact-us.reply', $message->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="admin_reply">{{ __('Admin Reply') }}</label>
                                <textarea name="admin_reply" id="admin_reply" class="form-control" rows="5" 
                                    placeholder="{{ __('Enter your reply...') }}" required></textarea>
                                @error('admin_reply')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> {{ __('Send Reply') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <hr>
            <div class="row">
                <div class="col-md-12">
                    @if($message->status !== 'archived')
                        <form action="{{ route('dashboard.contact-us.archive', $message->id) }}" 
                            method="POST" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-archive"></i> {{ __('Archive') }}
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('dashboard.contact-us.destroy', $message->id) }}" 
                        method="POST" class="d-inline" 
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var languageSwitcher = `
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-language"></i> {{ __('Language') }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ url('/lang/ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                            <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'ar' ? 'text-success' : 'text-secondary' }}"></i>
                            {{ __('Arabic') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/lang/en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'en' ? 'text-success' : 'text-secondary' }}"></i>
                            {{ __('English') }}
                        </a>
                    </div>
                </li>
            `;
            
            var topnavRight = $('.navbar-nav.topnav-right, .navbar-nav:has(.fa-expand)').first();
            if (topnavRight.length > 0) {
                topnavRight.append(languageSwitcher);
            } else {
                $('.navbar-nav').last().append(languageSwitcher);
            }
        });
    </script>
@stop

