@extends('adminlte::page')

@section('title', __('Users'))

@include('components.dashboard-layout')

@section('content_header')
    <h1>{{ __('Users') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>{{ __('Users') }}</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createuserModal">
                <i class="fas fa-plus"></i> {{ __('Add New') }}
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.users.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="filters[name]" class="form-control" 
                            placeholder="Search by Name..." value="{{ request('filters.name') }}">
                        <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                        <input type="hidden" name="offset" value="0">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="filters[email]" class="form-control" 
                            placeholder="Search by Email..." value="{{ request('filters.email') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Per Page Selector --}}
            <div class="mb-3 d-flex justify-content-end">
                <form action="{{ route('dashboard.users.index') }}" method="GET" class="form-inline">
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
                    @if(request('filters.email'))
                        <input type="hidden" name="filters[email]" value="{{ request('filters.email') }}">
                    @endif
                </form>
            </div>

            {{-- Users Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Provider</th>
                            <th style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $user->id }}</span></td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->family_name)
                                        <br><small class="text-muted">{{ $user->family_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>
                                    @if($user->is_active ?? true)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_verified)
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Verified</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-times"></i> Not Verified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_provider)
                                        <span class="badge badge-info"><i class="fas fa-user-md"></i> Provider</span>
                                    @else
                                        <span class="badge badge-secondary">Regular User</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#edituserModal{{ $user->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.users.toggle-provider', $user->id) }}" 
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to toggle provider status?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" 
                                                title="{{ $user->is_provider ? 'Remove Provider' : 'Make Provider' }}">
                                                <i class="fas fa-user-md"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.users.toggle-status', $user->id) }}" 
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to toggle user status?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                title="{{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.users.destroy', $user->id) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @include('dashboard.Users.modals.edit', ['user' => $user, 'cities' => $cities])
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No users found.</p>
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
                        <span>Showing {{ ($offset + 1) }} to {{ min($offset + $limit, $totalCount) }} of {{ $totalCount }} users</span>
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            {{-- Previous Button --}}
                            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('dashboard.users.index', array_merge(request()->except('offset'), ['offset' => max(0, $offset - $limit)])) }}">Previous</a>
                            </li>

                            {{-- Page Numbers --}}
                            @php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                            @endphp

                            @if($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('dashboard.users.index', array_merge(request()->except('offset'), ['offset' => 0])) }}">1</a>
                                </li>
                                @if($startPage > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($i = $startPage; $i <= $endPage; $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link" href="{{ route('dashboard.users.index', array_merge(request()->except('offset'), ['offset' => ($i - 1) * $limit])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($endPage < $totalPages)
                                @if($endPage < $totalPages - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('dashboard.users.index', array_merge(request()->except('offset'), ['offset' => ($totalPages - 1) * $limit])) }}">{{ $totalPages }}</a>
                                </li>
                            @endif

                            {{-- Next Button --}}
                            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('dashboard.users.index', array_merge(request()->except('offset'), ['offset' => min($offset + $limit, ($totalPages - 1) * $limit)])) }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Users.modals.create', ['cities' => $cities])
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
        console.log("Users Index Page Loaded");
    </script>
@stop

