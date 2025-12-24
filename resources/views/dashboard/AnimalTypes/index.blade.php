@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Animal Types</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createanimalTypeModal">Add Animal Type</button>

        </div>

        <div class="card-body"> <form action="{{ route('dashboard.animal_types.index') }}" method="GET" class="form-inline">
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
                    @forelse($AnimalTypes as $animalType)
                        <tr>
                            <td><img src="{{ asset($animalType->image) }}" width="80"></td>

                            <td><strong>{{ $animalType->name_ar }}</strong></td>
                            <td><strong>{{ $animalType->name_en }}</strong></td>
                            <td>

                                <button class="btn btn-sm btn-info edit-animal-type-btn" data-toggle="modal"
                                    data-target="#editanimalTypeModal{{ $animalType->id }}" data-id="{{ $animalType->id }}"
                                    data-name_ar="{{ $animalType->name_ar }}" data-name_en="{{ $animalType->name_en }}"
                                    data-image="{{ $animalType->image }}">
                                    Edit
                                </button>

                                <form action="{{ route('dashboard.animal_types.destroy', $animalType) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>




                        <!-- Edit Modal for Animal Type -->
                        @include('dashboard.AnimalTypes.modals.edit', ['animalType' => $animalType])


                    @empty
                        <tr>
                            <td colspan="4">No Animal Types found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @include('dashboard.AnimalTypes.modals.create')
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


