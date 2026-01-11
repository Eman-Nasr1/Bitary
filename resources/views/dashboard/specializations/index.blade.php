@extends('adminlte::page')

@section('title', __('Specializations'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Specializations') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Specializations') }}</h3>
            <a href="{{ route('dashboard.specializations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Search --}}
            <form action="{{ route('dashboard.specializations.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.specializations.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Specializations Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Courses Count</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specializations as $specialization)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $specialization->id }}</span></td>
                                <td><strong>{{ $specialization->name_en }}</strong></td>
                                <td><strong>{{ $specialization->name_ar }}</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $specialization->courses->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.specializations.show', $specialization->id) }}" 
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.specializations.edit', $specialization->id) }}" 
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.specializations.destroy', $specialization->id) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this specialization?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No specializations found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $specializations->links() }}
            </div>
        </div>
    </div>
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
    </style>
@stop

@section('js')
    <script>
        console.log("Specializations Index Page Loaded");
    </script>
@stop

