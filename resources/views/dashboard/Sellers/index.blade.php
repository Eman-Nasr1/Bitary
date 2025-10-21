@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Sellers</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createsellerModal">Add Seller</button>

        </div>

        <div class="card-body"> <form action="{{ route('dashboard.sellers.index') }}" method="GET" class="form-inline">
            <input type="text" name="filters[name_ar]" class="form-control mr-2" placeholder="Search by Arabic Name"
                value="{{ request('filters.name_ar') }}">
            <input type="text" name="filters[name_en]" class="form-control mr-2" placeholder="Search by English Name"
                value="{{ request('filters.name_en') }}">

            <input type="text" name="filters[description_ar]" class="form-control mr-2" placeholder="Search by Arabic Description "
                value="{{ request('filters.description_ar') }}">
            <input type="text" name="filters[description_en]" class="form-control mr-2" placeholder="Search by English Description"
                value="{{ request('filters.description_en') }}">

            <input type="text" name="filters[phone]" class="form-control mr-2" placeholder="Search by phone "
                value="{{ request('filters.phone') }}">
            <input type="text" name="filters[availability]" class="form-control mr-2" placeholder="Search by availability"
                value="{{ request('filters.availability') }}">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
<br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name Ar</th>
                        <th>Name En</th>

                        <th>Description Ar</th>
                        <th>Description En</th>
                          <th>phone</th>
                        <th>availability</th>
                        <th>Rate </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $seller)
                        <tr>
                            <td><img src="{{ asset($seller->image) }}" width="80"></td>

                            <td><strong>{{ $seller->name_ar }}</strong></td>
                            <td><strong>{{ $seller->name_en }}</strong></td>

                            <td><strong>{{ $seller->description_ar }}</strong></td>
                            <td><strong>{{ $seller->description_en }}</strong></td>
                            <td><strong>{{ $seller->phone }}</strong></td>
                            <td><strong>{{ $seller->availability }}</strong></td>
                            <td><strong>{{ $seller->rate }}</strong></td>
                            <td>

                                <button class="btn btn-sm btn-info edit-seller-btn" data-toggle="modal"
                                    data-target="#editsellerModal{{ $seller->id }}" data-id="{{ $seller->id }}"
                                    data-name_ar="{{ $seller->name_ar }}" data-name_en="{{ $seller->name_en }}"
                                    data-description_ar="{{ $seller->description_ar }}" data-description_en="{{ $seller->description_en }}"
                                    data-description_ar="{{ $seller->phone }}" data-description_en="{{ $seller->availability }}"
                                    data-image="{{ $seller->image }}">
                                    Edit
                                </button>

                                <form action="{{ route('dashboard.sellers.destroy', $seller) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>




                        <!-- Edit Modal for Parent -->
                        @include('dashboard.Sellers.modals.edit', ['seller' => $seller])


                    @empty
                        <tr>
                            <td colspan="2">No sellers found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @include('dashboard.Sellers.modals.create')
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
