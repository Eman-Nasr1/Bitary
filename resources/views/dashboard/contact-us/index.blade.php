@extends('adminlte::page')

@section('title', __('Contact Us Messages'))

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
    <h1><i class="fas fa-envelope"></i> {{ __('Contact Us Messages') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Messages') }}</h3>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.contact-us.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                            placeholder="{{ __('Search by name, email, subject or message...') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>{{ __('Read') }}</option>
                            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>{{ __('Replied') }}</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>{{ __('Archived') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> {{ __('Search') }}
                        </button>
                        <a href="{{ route('dashboard.contact-us.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>

            {{-- Messages Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th style="width: 200px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr class="{{ $message->status === 'new' ? 'table-warning' : '' }}">
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->phone ?? '-' }}</td>
                                <td>{{ $message->subject ?? '-' }}</td>
                                <td>
                                    @if($message->status === 'new')
                                        <span class="badge badge-warning">{{ __('New') }}</span>
                                    @elseif($message->status === 'read')
                                        <span class="badge badge-info">{{ __('Read') }}</span>
                                    @elseif($message->status === 'replied')
                                        <span class="badge badge-success">{{ __('Replied') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('Archived') }}</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.contact-us.show', $message->id) }}" 
                                        class="btn btn-sm btn-info" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($message->status !== 'archived')
                                        <form action="{{ route('dashboard.contact-us.archive', $message->id) }}" 
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ __('Archive') }}">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('dashboard.contact-us.destroy', $message->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('No contact messages found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $messages->links() }}
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

