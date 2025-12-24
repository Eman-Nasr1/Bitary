<!-- Edit Animal Type Modal -->
<div class="modal fade" id="editanimalTypeModal{{ $animalType->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editanimalTypeForm" method="POST" action="{{ route('dashboard.animal_types.update', $animalType->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Animal Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Animal Type Name En</label>
            <input type="text" name="name_en" id="editanimalTypeNameEn" value="{{ old('name_en', $animalType->name_en) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Animal Type Name Ar</label>
            <input type="text" name="name_ar" id="editanimalTypeNameAr" value="{{ old('name_ar', $animalType->name_ar) }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="createAnimalTypeImage">Image</label>
            <input type="file" name="image" id="createAnimalTypeImage" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">تحديث</button>
        </div>
      </div>
    </form>
  </div>
</div>


