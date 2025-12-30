@extends('adminlte::page')

@section('title', 'Provider Request Details')

@section('content_header')
    <h1>Provider Request Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Request #{{ $request->id }}</h3>
            <a href="{{ route('dashboard.provider-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Request Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Status:</th>
                            <td>
                                @if($request->status == 'pending')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted Date:</th>
                            <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @if($request->reviewed_at)
                            <tr>
                                <th>Reviewed Date:</th>
                                <td>{{ $request->reviewed_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Reviewed By:</th>
                                <td>{{ $request->reviewedBy->name ?? 'N/A' }}</td>
                            </tr>
                        @endif
                        @if($request->admin_note)
                            <tr>
                                <th>Admin Note:</th>
                                <td>{{ $request->admin_note }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">User Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">User Name:</th>
                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>User Email:</th>
                            <td>{{ $request->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>User Phone:</th>
                            <td>{{ $request->user->phone ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Provider Information</h5>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="20%">Entity Name:</th>
                            <td><strong>{{ $request->entity_name }}</strong></td>
                        </tr>
                        @if($request->specialty)
                            <tr>
                                <th>Specialty:</th>
                                <td>{{ $request->specialty }}</td>
                            </tr>
                        @endif
                        @if($request->degree)
                            <tr>
                                <th>Degree:</th>
                                <td>{{ $request->degree }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Email:</th>
                            <td>{{ $request->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $request->phone }}</td>
                        </tr>
                        @if($request->whatsapp)
                            <tr>
                                <th>WhatsApp:</th>
                                <td>{{ $request->whatsapp }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Address:</th>
                            <td>{{ $request->address }}</td>
                        </tr>
                        @if($request->google_maps_link)
                            <tr>
                                <th>Google Maps:</th>
                                <td><a href="{{ $request->google_maps_link }}" target="_blank">{{ $request->google_maps_link }}</a></td>
                            </tr>
                        @endif
                        @if($request->id_document)
                            <tr>
                                <th>ID Document:</th>
                                <td>
                                    <a href="{{ asset('storage/' . $request->id_document) }}" 
                                       target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file"></i> View Document
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if($request->license_document)
                            <tr>
                                <th>License Document:</th>
                                <td>
                                    <a href="{{ asset('storage/' . $request->license_document) }}" 
                                       target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file"></i> View Document
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($request->status == 'pending')
                <hr>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#approveModal{{ $request->id }}">
                        <i class="fas fa-check"></i> Approve Request
                    </button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal{{ $request->id }}">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                </div>
                @include('dashboard.ProviderRequests.modals.approve', ['request' => $request])
                @include('dashboard.ProviderRequests.modals.reject', ['request' => $request])
            @endif
        </div>
    </div>
@stop

