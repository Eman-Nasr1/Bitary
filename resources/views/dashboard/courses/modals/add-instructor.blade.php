<div class="modal fade" id="addInstructorModal" tabindex="-1" aria-labelledby="addInstructorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addInstructorModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Instructor
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addInstructorForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="instructor_name_ar">Name (Arabic) <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" id="instructor_name_ar" class="form-control" required>
                        <span class="text-danger" id="name_ar_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_name_en">Name (English)</label>
                        <input type="text" name="name_en" id="instructor_name_en" class="form-control">
                        <span class="text-danger" id="name_en_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_bio_ar">Bio (Arabic)</label>
                        <textarea name="bio_ar" id="instructor_bio_ar" class="form-control" rows="2"></textarea>
                        <span class="text-danger" id="bio_ar_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_bio_en">Bio (English)</label>
                        <textarea name="bio_en" id="instructor_bio_en" class="form-control" rows="2"></textarea>
                        <span class="text-danger" id="bio_en_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_email">Email</label>
                        <input type="email" name="email" id="instructor_email" class="form-control">
                        <span class="text-danger" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_phone">Phone</label>
                        <input type="text" name="phone" id="instructor_phone" class="form-control">
                        <span class="text-danger" id="phone_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="instructor_image">Image</label>
                        <input type="file" name="image" id="instructor_image" class="form-control" accept="image/*">
                        <span class="text-danger" id="image_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Add Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addInstructorForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var url = '{{ route("dashboard.courses.instructors.store") }}';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Add new instructor to select
                    var newOption = new Option(
                        response.instructor.name_ar + (response.instructor.name_en ? ' (' + response.instructor.name_en + ')' : ''),
                        response.instructor.id,
                        true,
                        true
                    );
                    $('#instructors').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#addInstructorModal').modal('hide');
                    $('#addInstructorForm')[0].reset();
                    
                    // Show success message
                    alert(response.message || 'Instructor added successfully!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        var errorSpan = $('#' + key + '_error');
                        if (errorSpan.length) {
                            errorSpan.text(value[0]);
                        } else {
                            var field = $('#instructor_' + key);
                            if (field.length) {
                                field.after('<span class="text-danger d-block" id="' + key + '_error">' + value[0] + '</span>');
                            }
                        }
                    });
                } else {
                    var errorMsg = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    alert(errorMsg);
                }
            }
        });
    });
    
    // Clear errors when modal is closed
    $('#addInstructorModal').on('hidden.bs.modal', function() {
        $('#addInstructorForm')[0].reset();
        $('span.text-danger').text('');
    });
});
</script>

