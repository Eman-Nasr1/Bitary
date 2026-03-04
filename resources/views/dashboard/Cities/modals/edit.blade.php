<!-- Edit City Modal -->
<div class="modal fade" id="editcityModal{{ $city->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editcityForm" method="POST" action="{{ route('dashboard.cities.update', $city->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit City</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>City Name (Arabic)</label>
            <input type="text" name="name_ar" value="{{ old('name_ar', $city->name_ar ?? $city->name) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>City Name (English)</label>
            <input type="text" name="name_en" value="{{ old('name_en', $city->name_en ?? $city->name) }}" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">تحديث</button>
        </div>
      </div>
    </form>
  </div>
</div>

