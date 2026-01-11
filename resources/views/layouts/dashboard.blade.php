@extends('adminlte::page')

@section('title', $title ?? __('Dashboard'))

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

