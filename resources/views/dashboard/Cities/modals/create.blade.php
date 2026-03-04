<div class="modal fade" id="createcityModal" tabindex="-1" aria-labelledby="createcityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('dashboard.cities.store') }}" method="POST">
      @csrf

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Add City</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>City Name (Arabic)</label>
            <input type="text" name="name_ar" class="form-control" required>
          </div>
          <div class="form-group">
            <label>City Name (English)</label>
            <input type="text" name="name_en" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>

