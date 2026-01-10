@extends('adminlte::page')

@section('title', 'Market Prices')

@section('content_header')
    <h1>Market Prices (Bourse)</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Market Prices</h3>
            <a href="{{ route('dashboard.market-prices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Price
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.market-prices.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by product name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="trend" class="form-control">
                            <option value="">All Trends</option>
                            <option value="up" {{ request('trend') == 'up' ? 'selected' : '' }}>Up</option>
                            <option value="down" {{ request('trend') == 'down' ? 'selected' : '' }}>Down</option>
                            <option value="stable" {{ request('trend') == 'stable' ? 'selected' : '' }}>Stable</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.market-prices.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Prices Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Product Name (AR)</th>
                            <th>Product Name (EN)</th>
                            <th>Price</th>
                            <th>Currency</th>
                            <th>Change %</th>
                            <th>Trend</th>
                            <th>Updated At</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $price)
                            <tr>
                                <td>{{ $price->id }}</td>
                                <td>{{ $price->product_name_ar }}</td>
                                <td>{{ $price->product_name_en ?? '-' }}</td>
                                <td><strong>{{ number_format($price->price, 2) }}</strong></td>
                                <td>{{ $price->currency }}</td>
                                <td>
                                    @if($price->change_percent !== null)
                                        <span class="badge badge-{{ $price->change_percent > 0 ? 'success' : ($price->change_percent < 0 ? 'danger' : 'secondary') }}">
                                            {{ $price->change_percent > 0 ? '+' : '' }}{{ number_format($price->change_percent, 2) }}%
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($price->trend == 'up')
                                        <span class="badge badge-success"><i class="fas fa-arrow-up"></i> Up</span>
                                    @elseif($price->trend == 'down')
                                        <span class="badge badge-danger"><i class="fas fa-arrow-down"></i> Down</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-minus"></i> Stable</span>
                                    @endif
                                </td>
                                <td>{{ $price->updated_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.market-prices.edit', $price->id) }}" 
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.market-prices.destroy', $price->id) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this market price?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No market prices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $prices->links() }}
            </div>
        </div>
    </div>
@stop

