@extends('adminlte::page')

@section('title', 'Medicines')

@section('content_header')
    <h1>Medicines</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Medicines</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createMedicineModal">Add Medicine</button>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.medicines.index') }}" method="GET" class="form-inline">
                <input type="text" name="filters[name_ar]" class="form-control mr-2" placeholder="Search by Arabic Name"
                    value="{{ request('filters.name_ar') }}">
                <input type="text" name="filters[name_en]" class="form-control mr-2" placeholder="Search by English Name"
                    value="{{ request('filters.name_en') }}">
                <input type="text" name="filters[description_ar]" class="form-control mr-2"
                    placeholder="Search by Arabic Description" value="{{ request('filters.description_ar') }}">
                <input type="text" name="filters[description_en]" class="form-control mr-2"
                    placeholder="Search by English Description" value="{{ request('filters.description_en') }}">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </form>

            <br>

            {{-- Medicines Table --}}
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name (Ar)</th>
                        <th>Name (En)</th>
                        <th>Title (Ar)</th>
                        <th>Title (En)</th>
                        <th>Description (Ar)</th>
                        <th>Description (En)</th>
                        <th>Category</th>
                        <th>Seller</th>
                        <th>Animals</th>
                        <th>Weight</th>
                        <th>Dimensions</th>
                        <th>Product Type</th>
                        <th>Manufacturer</th>
                        <th>Return Policy</th>
                        <th>Exchange Policy</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        <tr>
                            <td>

                                <img src="{{ asset($medicine->image ) }}" width="80">


                            </td>

                            <td>{{ $medicine->name_ar }}</td>
                            <td>{{ $medicine->name_en }}</td>
                            <td>{{ $medicine->title_ar ?? '—' }}</td>
                            <td>{{ $medicine->title_en ?? '—' }}</td>
                            <td>{{ $medicine->description_ar }}</td>
                            <td>{{ $medicine->description_en }}</td>
                            <td>{{ optional($medicine->category)->name ?? '—' }}</td>
                            <td>{{ optional($medicine->seller)->name_en ?? '—' }}</td>

                            <td>
                                @if ($medicine->animals->isNotEmpty())
                                    <ul class="mb-0">
                                        @foreach ($medicine->animals as $animal)
                                            <li>{{ $animal->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    —
                                @endif
                            </td>

                            <td>{{ $medicine->weight ?? '—' }}</td>
                            <td>{{ $medicine->dimensions ?? '—' }}</td>
                            <td>{{ $medicine->product_type }}</td>
                            <td>{{ $medicine->manufacturer }}</td>
                            <td>{{ $medicine->return_policy }}</td>
                            <td>{{ $medicine->exchange_policy }}</td>
                            <td>{{ $medicine->price }}</td>
                            <td>{{ $medicine->discount_percentage }}%</td>
                            <td>{{ $medicine->final_price }}</td>
                            <td>{{ $medicine->rating ?? '0' }}</td>

                            <td>
                                {{-- Edit Button --}}
                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#editMedicineModal{{ $medicine->id }}">
                                    Edit
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('dashboard.medicines.destroy', $medicine) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this medicine?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('dashboard.Medicines.modals.edit', ['medicine' => $medicine])

                    @empty
                        <tr>
                            <td colspan="21" class="text-center">No medicines found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('dashboard.Medicines.modals.create')
@stop

@section('css')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Medicines Index Page Loaded");
    </script>


@stop
