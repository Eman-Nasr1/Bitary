<div class="modal fade" id="createanimalTypeModal" tabindex="-1" aria-labelledby="createanimalTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('dashboard.animal_types.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Animal Type Name</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Animal Type Name En</label>
            <input type="text" name="name_en" class="form-control" required>

          </div>
          <div class="form-group">
            <label>Animal Type Name Ar</label>
            <input type="text" name="name_ar" class="form-control" required>

          </div>
          <div class="form-group">
            <label for="createanimalTypeImage">Image</label>
            <input type="file" name="image" required id="createanimalTypeImage" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>


