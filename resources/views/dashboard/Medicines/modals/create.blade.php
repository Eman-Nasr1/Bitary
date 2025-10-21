<div class="modal fade" id="createMedicineModal" tabindex="-1" aria-labelledby="createMedicineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dashboard.medicines.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"> Medicine Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Name --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name (EN)</label>
                            <input type="text" name="name_en" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Name (AR)</label>
                            <input type="text" name="name_ar" class="form-control" required>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Title (EN)</label>
                            <input type="text" name="title_en" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Title (AR)</label>
                            <input type="text" name="title_ar" class="form-control">
                        </div>
                    </div>

                    {{-- Price, Discount, Quantity --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Discount (%)</label>
                            <input type="number" step="0.01" name="discount_percentage" class="form-control"
                                value="0">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="0">
                        </div>
                    </div>


                    {{-- Description --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Description (EN)</label>
                            <textarea name="description_en" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description (AR)</label>
                            <textarea name="description_ar" class="form-control"></textarea>
                        </div>
                    </div>

                    {{-- Weight & Dimensions --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Weight</label>
                            <input type="text" name="weight" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Dimensions</label>
                            <input type="text" name="dimensions" class="form-control">
                        </div>
                    </div>

                    {{-- Relations --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Seller</label>
                            <select name="seller_id" class="form-control" required>
                                <option value="">Select Seller</option>
                                @foreach ($sellers as $seller)
                                    <option value="{{ $seller->id }}">{{ $seller->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Animals (multi-select) --}}
                    <div class="form-group">
                        <label>Animals</label>
                        <select name="animals[]" class="form-control" multiple>
                            @foreach ($animals as $animal)
                                <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Product Type --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Product Type (EN)</label>
                            <input type="text" name="product_type_en" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Type (AR)</label>
                            <input type="text" name="product_type_ar" class="form-control" required>
                        </div>
                    </div>

                    {{-- Manufacturer --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Manufacturer (EN)</label>
                            <input type="text" name="manufacturer_en" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Manufacturer (AR)</label>
                            <input type="text" name="manufacturer_ar" class="form-control">
                        </div>
                    </div>

                    {{-- Return & Exchange Policy --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Return Policy (EN)</label>
                            <textarea name="return_policy_en" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Return Policy (AR)</label>
                            <textarea name="return_policy_ar" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Exchange Policy (EN)</label>
                            <textarea name="exchange_policy_en" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Exchange Policy (AR)</label>
                            <textarea name="exchange_policy_ar" class="form-control"></textarea>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>
