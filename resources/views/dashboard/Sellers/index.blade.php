@extends('adminlte::page')

@section('title', __('Sellers'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Sellers') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Sellers') }}</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createsellerModal">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.sellers.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="filters[name_en]" class="form-control" 
                            placeholder="Search by Name..." value="{{ request('filters.name_en') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="filters[phone]" class="form-control" 
                            placeholder="Search by Phone..." value="{{ request('filters.phone') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.sellers.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Sellers Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Seller Name</th>
                            <th>Phone</th>
                            <th>Availability</th>
                            <th style="width: 80px;">Rating</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                            <tr>
                                <td>
                                    @if($seller->image_url)
                                        <img src="{{ $seller->image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $seller->name_en }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $seller->name_en }}</strong>
                                    @if($seller->name_ar)
                                        <br><small class="text-muted">{{ $seller->name_ar }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($seller->phone)
                                        <span class="badge badge-secondary">{{ $seller->phone }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($seller->availability)
                                        <span class="badge badge-success">{{ $seller->availability }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($seller->rating)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> {{ $seller->rating }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editsellerModal{{ $seller->id }}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.sellers.destroy', $seller) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this seller?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            @include('dashboard.Sellers.modals.edit', ['seller' => $seller])

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No sellers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Sellers.modals.create')
@stop

@section('css')
    <style>
        .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #343a40;
            color: white;
            font-weight: 600;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        .img-thumbnail {
            border-radius: 4px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Sellers Index Page Loaded");
    </script>
@stop
