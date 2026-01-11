@extends('adminlte::page')

@section('title', __('Animal Types'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Animal Types') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Animal Types') }}</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createanimalTypeModal">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.animal_types.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="filters[name_en]" class="form-control" 
                            placeholder="Search by Name..." value="{{ request('filters.name_en') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="filters[name_ar]" class="form-control" 
                            placeholder="بحث بالاسم..." value="{{ request('filters.name_ar') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.animal_types.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Animal Types Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Animal Type Name</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($AnimalTypes as $animalType)
                            <tr>
                                <td>
                                    @if($animalType->image_url)
                                        <img src="{{ $animalType->image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $animalType->name_en }}"
                                            onerror="console.error('Image failed to load:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="" 
                                            style="width: 60px; height: 60px; display: none;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @else
                                      
                                        <!-- Debug: Image path = {{ $animalType->image ?? 'NULL' }} -->
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $animalType->name_en }}</strong>
                                    @if($animalType->name_ar)
                                        <br><small class="text-muted">{{ $animalType->name_ar }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editanimalTypeModal{{ $animalType->id }}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.animal_types.destroy', $animalType) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this animal type?')">
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
                            @include('dashboard.AnimalTypes.modals.edit', ['animalType' => $animalType])

                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No animal types found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.AnimalTypes.modals.create')
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
        console.log("Animal Types Index Page Loaded");
    </script>
@stop
