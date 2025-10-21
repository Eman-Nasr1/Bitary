<div class="modal fade" id="createcategoryModal" tabindex="-1" aria-labelledby="createcategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title"> category Name</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>category Name En</label>
            <input type="text" name="name_en" class="form-control" required>

          </div>
          <div class="form-group">
            <label>category Name Ar</label>
            <input type="text" name="name_ar" class="form-control" required>

          </div>
          <div class="form-group">
            <label for="createcategoryImage">Image</label>
            <input type="file" name="image" required id="createcategoryImage" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>
