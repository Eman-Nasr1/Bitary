@extends('adminlte::page')

@section('title', 'View Contact Message')

@section('content_header')
    <h1><i class="fas fa-envelope-open"></i> View Contact Message</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Message Details</h3>
            <div>
                @if($message->status === 'new')
                    <form action="{{ route('dashboard.contact-us.mark-as-read', $message->id) }}" 
                        method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-check"></i> Mark as Read
                        </button>
                    </form>
                @endif
                @if($message->status !== 'archived')
                    <form action="{{ route('dashboard.contact-us.archive', $message->id) }}" 
                        method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                    </form>
                @endif
                <a href="{{ route('dashboard.contact-us.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Message Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Name:</strong>
                    <p class="mb-0">{{ $message->name }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong>
                    <p class="mb-0">
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </p>
                </div>
            </div>

            @if($message->phone)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Phone:</strong>
                        <p class="mb-0">
                            <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                        </p>
                    </div>
                </div>
            @endif

            @if($message->subject)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Subject:</strong>
                        <p class="mb-0">{{ $message->subject }}</p>
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-12">
                    <strong>Message:</strong>
                    <div class="border p-3 mt-2 bg-light">
                        <p class="mb-0">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Status & Dates --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Status:</strong>
                    @if($message->status === 'new')
                        <span class="badge badge-warning">New</span>
                    @elseif($message->status === 'read')
                        <span class="badge badge-info">Read</span>
                    @elseif($message->status === 'replied')
                        <span class="badge badge-success">Replied</span>
                    @else
                        <span class="badge badge-secondary">Archived</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <strong>Created At:</strong>
                    <p class="mb-0">{{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
                @if($message->read_at)
                    <div class="col-md-3">
                        <strong>Read At:</strong>
                        <p class="mb-0">{{ $message->read_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                @endif
                @if($message->replied_at)
                    <div class="col-md-3">
                        <strong>Replied At:</strong>
                        <p class="mb-0">{{ $message->replied_at->format('Y-m-d H:i:s') }}</p>
                        @if($message->repliedByAdmin)
                            <small class="text-muted">by {{ $message->repliedByAdmin->name }}</small>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Admin Reply Section --}}
            @if($message->admin_reply)
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Admin Reply:</strong>
                        <div class="border p-3 mt-2 bg-info text-white">
                            <p class="mb-0">{{ $message->admin_reply }}</p>
                        </div>
                        @if($message->repliedByAdmin)
                            <small class="text-muted">
                                Replied by: {{ $message->repliedByAdmin->name }} 
                                on {{ $message->replied_at->format('Y-m-d H:i:s') }}
                            </small>
                        @endif
                    </div>
                </div>
            @else
                {{-- Reply Form --}}
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4>Reply to Message</h4>
                        <form action="{{ route('dashboard.contact-us.reply', $message->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="admin_reply">Admin Reply</label>
                                <textarea name="admin_reply" id="admin_reply" class="form-control" rows="5" 
                                    placeholder="Enter your reply..." required></textarea>
                                @error('admin_reply')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <hr>
            <div class="row">
                <div class="col-md-12">
                    @if($message->status !== 'archived')
                        <form action="{{ route('dashboard.contact-us.archive', $message->id) }}" 
                            method="POST" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-archive"></i> Archive
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('dashboard.contact-us.destroy', $message->id) }}" 
                        method="POST" class="d-inline" 
                        onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

