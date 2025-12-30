<div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dashboard.provider-requests.approve', $request->id) }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="approveModalLabel">
                        <i class="fas fa-check"></i> Approve Provider Request
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to approve this provider request?</p>
                    <p><strong>Entity:</strong> {{ $request->entity_name }}</p>
                    <p><strong>User:</strong> {{ $request->user->name ?? 'N/A' }}</p>
                    
                    <div class="form-group mt-3">
                        <label for="approve_note{{ $request->id }}">Admin Note (Optional):</label>
                        <textarea name="admin_note" id="approve_note{{ $request->id }}" 
                            class="form-control" rows="3" 
                            placeholder="Add a note about this approval..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        This will automatically make the user a provider.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

