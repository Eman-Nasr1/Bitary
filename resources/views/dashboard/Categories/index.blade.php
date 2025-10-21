@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Categorys</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createcategoryModal">Add Category</button>

        </div>

        <div class="card-body"> <form action="{{ route('dashboard.categories.index') }}" method="GET" class="form-inline">
            <input type="text" name="filters[name_ar]" class="form-control mr-2" placeholder="Search by Arabic Name"
                value="{{ request('filters.name_ar') }}">
            <input type="text" name="filters[name_en]" class="form-control mr-2" placeholder="Search by English Name"
                value="{{ request('filters.name_en') }}">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
<br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name Ar</th>
                        <th>Name En</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><img src="{{ asset($category->image) }}" width="80"></td>

                            <td><strong>{{ $category->name_ar }}</strong></td>
                            <td><strong>{{ $category->name_en }}</strong></td>
                            <td>

                                <button class="btn btn-sm btn-info edit-category-btn" data-toggle="modal"
                                    data-target="#editcategoryModal{{ $category->id }}" data-id="{{ $category->id }}"
                                    data-name_ar="{{ $category->name_ar }}" data-name_en="{{ $category->name_en }}"
                                    data-image="{{ $category->image }}">
                                    Edit
                                </button>

                                <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>




                        <!-- Edit Modal for Parent -->
                    @include('dashboard.Categories.modals.edit', ['category' => $category])


                    @empty
                        <tr>
                            <td colspan="2">No categorys found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @include('dashboard.Categories.modals.create')
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
