@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Animals</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createanimalModal">Add animal</button>

        </div>

        <div class="card-body"> <form action="{{ route('dashboard.animals.index') }}" method="GET" class="form-inline">
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
                    @forelse($Animals as $animal)
                        <tr>
                            <td><img src="{{ asset($animal->image) }}" width="80"></td>

                            <td><strong>{{ $animal->name_ar }}</strong></td>
                            <td><strong>{{ $animal->name_en }}</strong></td>
                            <td>

                                <button class="btn btn-sm btn-info edit-animal-btn" data-toggle="modal"
                                    data-target="#editanimalModal{{ $animal->id }}" data-id="{{ $animal->id }}"
                                    data-name_ar="{{ $animal->name_ar }}" data-name_en="{{ $animal->name_en }}"
                                    data-image="{{ $animal->image }}">
                                    Edit
                                </button>

                                <form action="{{ route('dashboard.animals.destroy', $animal) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>




                        <!-- Edit Modal for Parent -->
                        @include('dashboard.Animals.modals.edit', ['animal' => $animal])


                    @empty
                        <tr>
                            <td colspan="2">No Animals found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @include('dashboard.Animals.modals.create')
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
