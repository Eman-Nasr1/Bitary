@extends('adminlte::page')

@section('title', 'Animals')

@section('content_header')
    <h1>Animals</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Animals</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createanimalModal">
                <i class="fas fa-plus"></i> Add Animal
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.animals.index') }}" method="GET" class="mb-3">
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
                        <a href="{{ route('dashboard.animals.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Animals Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Animal Name</th>
                            <th>Animal Type</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($Animals as $animal)
                            <tr>
                                <td>
                                    @if($animal->image_url)
                                        <img src="{{ $animal->image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $animal->name_en }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $animal->name_en }}</strong>
                                    @if($animal->name_ar)
                                        <br><small class="text-muted">{{ $animal->name_ar }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($animal->animalType)
                                        <span class="badge badge-info">{{ $animal->animalType->name }}</span>
                                    @else
                                        <span class="text-muted">No Type</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editanimalModal{{ $animal->id }}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.animals.destroy', $animal) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this animal?')">
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
                            @include('dashboard.Animals.modals.edit', ['animal' => $animal, 'animalTypes' => $animalTypes])

                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No animals found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Animals.modals.create', ['animalTypes' => $animalTypes])
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
        console.log("Animals Index Page Loaded");
    </script>
@stop
