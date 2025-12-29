<!-- Edit Medicine Modal -->
<div class="modal fade" id="editMedicineModal{{ $medicine->id }}" tabindex="-1" aria-labelledby="editMedicineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('dashboard.medicines.update', $medicine->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Name --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name (EN)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $medicine->name_en) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Name (AR)</label>
                            <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $medicine->name_ar) }}" required>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Title (EN)</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $medicine->title_en) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Title (AR)</label>
                            <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $medicine->title_ar) }}">
                        </div>
                    </div>

                    {{-- Price, Discount, Quantity --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $medicine->price) }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Discount (%)</label>
                            <input type="number" step="0.01" name="discount_percentage" class="form-control" value="{{ old('discount_percentage', $medicine->discount_percentage ?? 0) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $medicine->quantity ?? 0) }}">
                        </div>
                    </div>


                    {{-- Description --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Description (EN)</label>
                            <textarea name="description_en" class="form-control">{{ old('description_en', $medicine->description_en) }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description (AR)</label>
                            <textarea name="description_ar" class="form-control">{{ old('description_ar', $medicine->description_ar) }}</textarea>
                        </div>
                    </div>

                    {{-- Weight & Dimensions --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Weight</label>
                            <input type="text" name="weight" class="form-control" value="{{ old('weight', $medicine->weight) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Dimensions</label>
                            <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions', $medicine->dimensions) }}">
                        </div>
                    </div>

                    {{-- Relations --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $medicine->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Seller</label>
                            <select name="seller_id" class="form-control" required>
                                <option value="">Select Seller</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ $seller->id == $medicine->seller_id ? 'selected' : '' }}>
                                        {{ $seller->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Animals (multi-select) --}}
                    <div class="form-group">
                        <label>Animals</label>
                        <select name="animals[]" class="form-control animals-select" multiple>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}" {{ in_array($animal->id, $medicine->animals->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $animal->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Select one or more animals. You can search by typing.</small>
                    </div>

                    {{-- Product Type --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Product Type (EN)</label>
                            <input type="text" name="product_type_en" class="form-control" value="{{ old('product_type_en', $medicine->product_type_en) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Type (AR)</label>
                            <input type="text" name="product_type_ar" class="form-control" value="{{ old('product_type_ar', $medicine->product_type_ar) }}" required>
                        </div>
                    </div>

                    {{-- Manufacturer --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Manufacturer (EN)</label>
                            <input type="text" name="manufacturer_en" class="form-control" value="{{ old('manufacturer_en', $medicine->manufacturer_en) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Manufacturer (AR)</label>
                            <input type="text" name="manufacturer_ar" class="form-control" value="{{ old('manufacturer_ar', $medicine->manufacturer_ar) }}">
                        </div>
                    </div>

                    {{-- Return & Exchange Policy --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Return Policy (EN)</label>
                            <textarea name="return_policy_en" class="form-control">{{ old('return_policy_en', $medicine->return_policy_en) }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Return Policy (AR)</label>
                            <textarea name="return_policy_ar" class="form-control">{{ old('return_policy_ar', $medicine->return_policy_ar) }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Exchange Policy (EN)</label>
                            <textarea name="exchange_policy_en" class="form-control">{{ old('exchange_policy_en', $medicine->exchange_policy_en) }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Exchange Policy (AR)</label>
                            <textarea name="exchange_policy_ar" class="form-control">{{ old('exchange_policy_ar', $medicine->exchange_policy_ar) }}</textarea>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                        @if($medicine->image_url)
                            <img src="{{ $medicine->image_url }}" width="80" class="img-thumbnail mt-2" alt="{{ $medicine->name_en }}">
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </div>
        </form>
    </div>
</div>
