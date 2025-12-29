@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Products</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Products</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createMedicineModal">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.medicines.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="filters[name_en]" class="form-control" 
                            placeholder="Search by Name..." value="{{ request('filters.name_en') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="filters[category_id]" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('filters.category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.medicines.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Products Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Final Price</th>
                            <th style="width: 80px;">Rating</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                            <tr>
                                <td>
                                    @if($medicine->image_url)
                                        <img src="{{ $medicine->image_url }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $medicine->name_en }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $medicine->name_en }}</strong>
                                    @if($medicine->name_ar)
                                        <br><small class="text-muted">{{ $medicine->name_ar }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($medicine->category)
                                        <span class="badge badge-info">{{ $medicine->category->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($medicine->seller)
                                        <span class="badge badge-secondary">{{ $medicine->seller->name_en }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($medicine->discount_percentage > 0)
                                        <del class="text-muted">{{ number_format($medicine->price, 2) }} EGP</del>
                                    @else
                                        <strong>{{ number_format($medicine->price, 2) }} EGP</strong>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($medicine->final_price, 2) }} EGP</strong>
                                    @if($medicine->discount_percentage > 0)
                                        <br><small class="badge badge-danger">-{{ $medicine->discount_percentage }}%</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($medicine->rating)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> {{ $medicine->rating }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewMedicineModal{{ $medicine->id }}" 
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editMedicineModal{{ $medicine->id }}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.medicines.destroy', $medicine) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- View Details Modal --}}
                            @include('dashboard.Medicines.modals.view', ['medicine' => $medicine])

                            {{-- Edit Modal --}}
                            @include('dashboard.Medicines.modals.edit', ['medicine' => $medicine])

                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">No products found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Medicines.modals.create')
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
        /* Fix Select2 dropdown z-index in modals - must be higher than modal z-index (1050) */
        .modal {
            z-index: 1050;
        }
        .modal-backdrop {
            z-index: 1040;
        }
        
        /* Force Select2 dropdown to open below the input */
        .select2-container--open .select2-dropdown {
            top: 100% !important;
            margin-top: 2px;
        }
        
        /* Make chips expand inside input if overflow */
        .select2-selection--multiple {
            min-height: 38px;
            overflow-y: auto;
        }
        
        /* Select2 in modals */
        body.modal-open .select2-container {
            z-index: 10060 !important;
        }
        body.modal-open .select2-dropdown {
            z-index: 10060 !important;
        }
        body.modal-open .select2-search {
            z-index: 10060 !important;
        }
        body.modal-open .select2-container--open {
            z-index: 10060 !important;
        }
        .modal .select2-container--open {
            z-index: 10060 !important;
        }
        .modal .select2-dropdown {
            z-index: 10060 !important;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Products Index Page Loaded");
        
        // Initialize Select2 for Animals multi-select in modals
        $(document).ready(function() {
            // Wait for Select2 to be available
            if (typeof $.fn.select2 === 'undefined') {
                console.error('Select2 is not loaded');
                return;
            }
            
            // Global handler for Select2 open event to fix z-index for all modals
            $(document).on('select2:open', function(e) {
                setTimeout(function() {
                    $('.select2-container--open').css('z-index', '10060');
                    $('.select2-dropdown').css('z-index', '10060');
                }, 10);
            });
            
            // For create modal
            $('#createMedicineModal').on('shown.bs.modal', function () {
                var $modal = $(this);
                var $select = $modal.find('#animalsSelect');
                if ($select.length) {
                    // Destroy existing Select2 instance if any
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }
                    
                    // Small delay to ensure modal is fully rendered
                    setTimeout(function() {
                        // Initialize Select2 - use modal-content as parent to allow scrolling
                        $select.select2({
                            placeholder: 'Select animals...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $modal.find('.modal-content'),
                            closeOnSelect: false
                        });
                    }, 150);
                }
            });
            
            // For edit modals - initialize when shown
            $(document).on('shown.bs.modal', '[id^="editMedicineModal"]', function () {
                var $modal = $(this);
                var $select = $modal.find('select.animals-select');
                if ($select.length) {
                    // Destroy existing Select2 instance if any
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }
                    
                    // Small delay to ensure modal is fully rendered
                    setTimeout(function() {
                        // Initialize Select2 - use modal-content as parent to allow scrolling
                        $select.select2({
                            placeholder: 'Select animals...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $modal.find('.modal-content'),
                            closeOnSelect: false
                        });
                    }, 150);
                }
            });
            
            // Destroy Select2 when modal is hidden to avoid conflicts
            $('#createMedicineModal, [id^="editMedicineModal"]').on('hidden.bs.modal', function () {
                $(this).find('select[name="animals[]"]').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });
        });
    </script>
@stop
