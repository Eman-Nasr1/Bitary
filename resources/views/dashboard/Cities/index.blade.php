@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Cities</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createcityModal">Add City</button>

        </div>

        <div class="card-body"> 
            <form action="{{ route('dashboard.cities.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="filters[name]" class="form-control mr-2" placeholder="Search by Name"
                    value="{{ request('filters.name') }}">
                <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                <input type="hidden" name="offset" value="0">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('dashboard.cities.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
            
            {{-- Per Page Selector --}}
            <div class="mb-2">
                <form action="{{ route('dashboard.cities.index') }}" method="GET" class="form-inline">
                    <label for="perPage" class="mr-2">Per Page:</label>
                    <select name="limit" id="perPage" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
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
<br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td><strong>{{ $city->name }}</strong></td>
                            <td>

                                <button class="btn btn-sm btn-info edit-city-btn" data-toggle="modal"
                                    data-target="#editcityModal{{ $city->id }}" data-id="{{ $city->id }}"
                                    data-name="{{ $city->name }}">
                                    Edit
                                </button>

                                <form action="{{ route('dashboard.cities.destroy', $city) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>




                        <!-- Edit Modal for City -->
                        @include('dashboard.Cities.modals.edit', ['city' => $city])


                    @empty
                        <tr>
                            <td colspan="3">No Cities found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            
            {{-- Pagination --}}
            @if($totalPages > 1)
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span>Showing {{ ($offset + 1) }} to {{ min($offset + $limit, $totalCount) }} of {{ $totalCount }} cities</span>
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        {{-- Previous Button --}}
                        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => max(0, $offset - $limit)])) }}">Previous</a>
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
                            <a class="page-link" href="{{ route('dashboard.cities.index', array_merge(request()->all(), ['offset' => min($offset + $limit, ($totalPages - 1) * $limit)])) }}">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
    @include('dashboard.Cities.modals.create')
@stop



@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop

