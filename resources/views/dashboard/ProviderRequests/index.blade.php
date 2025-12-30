@extends('adminlte::page')

@section('title', 'Provider Requests')

@section('content_header')
    <h1>Provider Requests</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Provider Requests</h3>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.provider-requests.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="filters[status]" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('filters.status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('filters.status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('filters.status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                        <input type="hidden" name="offset" value="0">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('dashboard.provider-requests.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        <div class="d-inline-block ml-3">
                            <label for="perPage" class="mr-2">Per Page:</label>
                            <form action="{{ route('dashboard.provider-requests.index') }}" method="GET" class="d-inline">
                                <select name="limit" id="perPage" class="form-control form-control-sm d-inline" 
                                    style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('limit', 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <input type="hidden" name="offset" value="0">
                                @if(request('filters.status'))
                                    <input type="hidden" name="filters[status]" value="{{ request('filters.status') }}">
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Provider Requests Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>User</th>
                            <th>Entity Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $request->id }}</span></td>
                                <td>
                                    <strong>{{ $request->user->name ?? 'N/A' }}</strong>
                                    @if($request->user->email ?? null)
                                        <br><small class="text-muted">{{ $request->user->email }}</small>
                                    @endif
                                </td>
                                <td><strong>{{ $request->entity_name }}</strong></td>
                                <td>{{ $request->email }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i> Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $request->created_at->format('Y-m-d H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.provider-requests.show', $request->id) }}" 
                                            class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status == 'pending')
                                            <button class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#approveModal{{ $request->id }}" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#rejectModal{{ $request->id }}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @include('dashboard.ProviderRequests.modals.approve', ['request' => $request])
                            @include('dashboard.ProviderRequests.modals.reject', ['request' => $request])
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No provider requests found.</p>
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
                        <span>Showing {{ ($offset + 1) }} to {{ min($offset + $limit, $totalCount) }} of {{ $totalCount }} requests</span>
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('dashboard.provider-requests.index', array_merge(request()->except('offset'), ['offset' => max(0, $offset - $limit)])) }}">Previous</a>
                            </li>

                            @php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                            @endphp

                            @if($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('dashboard.provider-requests.index', array_merge(request()->except('offset'), ['offset' => 0])) }}">1</a>
                                </li>
                                @if($startPage > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($i = $startPage; $i <= $endPage; $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link" href="{{ route('dashboard.provider-requests.index', array_merge(request()->except('offset'), ['offset' => ($i - 1) * $limit])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($endPage < $totalPages)
                                @if($endPage < $totalPages - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('dashboard.provider-requests.index', array_merge(request()->except('offset'), ['offset' => ($totalPages - 1) * $limit])) }}">{{ $totalPages }}</a>
                                </li>
                            @endif

                            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('dashboard.provider-requests.index', array_merge(request()->except('offset'), ['offset' => min($offset + $limit, ($totalPages - 1) * $limit)])) }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
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
        console.log("Provider Requests Index Page Loaded");
    </script>
@stop

