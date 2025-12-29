@extends('adminlte::page')

@section('title', 'Cities')

@section('content_header')
    <h1>Cities</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Cities</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createcityModal">
                <i class="fas fa-plus"></i> Add City
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.cities.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="filters[name]" class="form-control" 
                            placeholder="Search by Name..." value="{{ request('filters.name') }}">
                        <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                        <input type="hidden" name="offset" value="0">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.cities.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        <div class="d-inline-block ml-3">
                            <label for="perPage" class="mr-2">Per Page:</label>
                            <form action="{{ route('dashboard.cities.index') }}" method="GET" class="d-inline">
                                <select name="limit" id="perPage" class="form-control form-control-sm d-inline" 
                                    style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('limit', 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <input type="hidden" name="offset" value="0">
                                @if(request('filters.name'))
                                    <input type="hidden" name="filters[name]" value="{{ request('filters.name') }}">
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Cities Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>City Name</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">{{ $city->id }}</span>
                                </td>
                                <td>
                                    <strong>{{ $city->name }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editcityModal{{ $city->id }}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.cities.destroy', $city) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this city?')">
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
                            @include('dashboard.Cities.modals.edit', ['city' => $city])

                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No cities found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if(isset($totalPages) && $totalPages > 1)
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span>Showing {{ ($offset + 1) }} to {{ min($offset + $limit, $totalCount) }} of {{ $totalCount }} cities</span>
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        {{-- Previous Button --}}
                        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => max(0, $offset - $limit)])) }}">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                        @endphp

                        @if($startPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => 0])) }}">1</a>
                            </li>
                            @if($startPage > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        @for($i = $startPage; $i <= $endPage; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => ($i - 1) * $limit])) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($endPage < $totalPages)
                            @if($endPage < $totalPages - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => ($totalPages - 1) * $limit])) }}">{{ $totalPages }}</a>
                            </li>
                        @endif

                        {{-- Next Button --}}
                        <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => min($offset + $limit, ($totalPages - 1) * $limit)])) }}">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Cities.modals.create')
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
        console.log("Cities Index Page Loaded");
    </script>
@stop
