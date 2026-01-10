@extends('adminlte::page')

@section('title', 'Edit Market Price')

@section('content_header')
    <h1>Edit Market Price</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Market Price</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.market-prices.update', $price->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_name_ar">Product Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="product_name_ar" id="product_name_ar" class="form-control" 
                                value="{{ old('product_name_ar', $price->product_name_ar) }}" required>
                            @error('product_name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_name_en">Product Name (English)</label>
                            <input type="text" name="product_name_en" id="product_name_en" class="form-control" 
                                value="{{ old('product_name_en', $price->product_name_en) }}">
                            @error('product_name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" 
                                value="{{ old('price', $price->price) }}" required>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency">Currency <span class="text-danger">*</span></label>
                            <input type="text" name="currency" id="currency" class="form-control" 
                                value="{{ old('currency', $price->currency) }}" required>
                            @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="change_percent">Change Percent (%)</label>
                            <input type="number" step="0.01" name="change_percent" id="change_percent" class="form-control" 
                                value="{{ old('change_percent', $price->change_percent) }}">
                            @error('change_percent')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Positive = up, Negative = down, 0 = stable</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="trend">Trend (Auto-calculated if change_percent provided)</label>
                    <select name="trend" id="trend" class="form-control">
                        <option value="stable" {{ old('trend', $price->trend) == 'stable' ? 'selected' : '' }}>Stable</option>
                        <option value="up" {{ old('trend', $price->trend) == 'up' ? 'selected' : '' }}>Up</option>
                        <option value="down" {{ old('trend', $price->trend) == 'down' ? 'selected' : '' }}>Down</option>
                    </select>
                    @error('trend')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Will be auto-calculated based on change_percent</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Market Price
                    </button>
                    <a href="{{ route('dashboard.market-prices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
<script>
    $(document).ready(function() {
        // Auto-calculate trend based on change_percent
        $('#change_percent').on('input', function() {
            const changePercent = parseFloat($(this).val()) || 0;
            let trend = 'stable';
            
            if (changePercent > 0) {
                trend = 'up';
            } else if (changePercent < 0) {
                trend = 'down';
            }
            
            $('#trend').val(trend);
        });
    });
</script>
@endpush

