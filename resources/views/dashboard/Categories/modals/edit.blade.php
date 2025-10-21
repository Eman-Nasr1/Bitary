<!-- Edit category Modal -->
<div class="modal fade" id="editcategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editcategoryForm" method="POST" action="{{ route('dashboard.categories.update', $category->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label> category Name En</label>
            <input type="text" name="name_en" id="editcategoryNameEn" value="{{ old('name_en', $category->name_en) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label> category Name Ar</label>
            <input type="text" name="name_ar" id="editcategoryNameAr" value="{{ old('name_ar', $category->name_ar) }}" class="form-control" required>
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
