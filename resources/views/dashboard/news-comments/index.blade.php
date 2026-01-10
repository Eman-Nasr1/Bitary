@extends('adminlte::page')

@section('title', 'News Comments')

@section('content_header')
    <h1>News Comments (Moderation)</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Comments</h3>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <form action="{{ route('dashboard.news-comments.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by comment or user name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="news_id" class="form-control" 
                            placeholder="News ID..." value="{{ request('news_id') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('dashboard.news-comments.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            {{-- Comments Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>News</th>
                            <th>User</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>
                                    <a href="{{ route('dashboard.news.show', $comment->news_id) }}" target="_blank">
                                        #{{ $comment->news_id }}: {{ Str::limit($comment->news->title_ar ?? 'N/A', 30) }}
                                    </a>
                                </td>
                                <td>
                                    {{ $comment->user_name ?? ($comment->user->name ?? 'Anonymous') }}
                                </td>
                                <td>
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($comment->comment, 100) }}
                                    </div>
                                </td>
                                <td>
                                    @if($comment->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($comment->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($comment->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td>{{ $comment->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($comment->status != 'approved')
                                            <form action="{{ route('dashboard.news-comments.approve', $comment->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($comment->status != 'rejected')
                                            <form action="{{ route('dashboard.news-comments.reject', $comment->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($comment->status != 'hidden')
                                            <form action="{{ route('dashboard.news-comments.hide', $comment->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary" title="Hide">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('dashboard.news-comments.destroy', $comment->id) }}" 
                                            method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- Full Comment Modal --}}
                            <tr class="collapse" id="commentModal{{ $comment->id }}">
                                <td colspan="7">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <strong>Full Comment:</strong>
                                            <p>{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No comments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@stop

