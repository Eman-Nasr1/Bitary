<div class="modal fade" id="createsellerModal" tabindex="-1" aria-labelledby="createsellerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('dashboard.sellers.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title"> Seller Name</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>seller Name En</label>
            <input type="text" name="name_en" class="form-control" required>

          </div>
          <div class="form-group">
            <label>seller Name Ar</label>
            <input type="text" name="name_ar" class="form-control" required>

          </div>
          <div class="form-group">
            <label>seller Description En</label>
            <input type="text" name="description_en" class="form-control" required>

          </div>
          <div class="form-group">
            <label>seller Description Ar</label>
            <input type="text" name="description_ar" class="form-control" required>

          </div>
          <div class="form-group">
            <label for="createsellerImage">Image</label>
            <input type="file" name="image" required id="createsellerImage" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>
