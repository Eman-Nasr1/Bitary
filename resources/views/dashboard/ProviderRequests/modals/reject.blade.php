<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dashboard.provider-requests.reject', $request->id) }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="rejectModalLabel">
                        <i class="fas fa-times"></i> Reject Provider Request
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to reject this provider request?</p>
                    <p><strong>Entity:</strong> {{ $request->entity_name }}</p>
                    <p><strong>User:</strong> {{ $request->user->name ?? 'N/A' }}</p>
                    
                    <div class="form-group mt-3">
                        <label for="reject_note{{ $request->id }}">Rejection Reason (Recommended):</label>
                        <textarea name="admin_note" id="reject_note{{ $request->id }}" 
                            class="form-control" rows="3" 
                            placeholder="Please provide a reason for rejection..."></textarea>
                        <small class="form-text text-muted">This note will be visible to the user.</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        This action cannot be undone. The user will remain a regular user.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

