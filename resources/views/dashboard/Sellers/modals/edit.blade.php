<!-- Edit seller Modal -->
<div class="modal fade" id="editsellerModal{{ $seller->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editsellerForm" method="POST" action="{{ route('dashboard.sellers.update', $seller->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Seller</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label> seller Name En</label>
            <input type="text" name="name_en" id="editsellerNameEn" value="{{ old('name_en', $seller->name_en) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label> seller Name Ar</label>
            <input type="text" name="name_ar" id="editsellerNameAr" value="{{ old('name_ar', $seller->name_ar) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label> seller description En</label>
            <input type="text" name="description_en" id="editsellerDescriptionEn" value="{{ old('description_en', $seller->description_en) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label> seller description Ar</label>
            <input type="text" name="description_ar" id="editsellerDescriptionAr" value="{{ old('description_ar', $seller->description_ar) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="createBannerImage">Image</label>
            <input type="file" name="image" id="createhealthIssueImage" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">تحديث</button>
        </div>
      </div>
    </form>
  </div>
</div>
