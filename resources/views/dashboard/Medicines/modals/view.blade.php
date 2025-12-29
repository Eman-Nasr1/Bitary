<!-- View Product Details Modal -->
<div class="modal fade" id="viewMedicineModal{{ $medicine->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">
                    <i class="fas fa-eye"></i> Product Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    {{-- Image --}}
                    <div class="col-md-4 text-center mb-3">
                        @if($medicine->image_url)
                            <img src="{{ $medicine->image_url }}" 
                                class="img-fluid img-thumbnail" 
                                alt="{{ $medicine->name_en }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Basic Info --}}
                    <div class="col-md-8">
                        <h4>{{ $medicine->name_en }}</h4>
                        @if($medicine->name_ar)
                            <p class="text-muted">{{ $medicine->name_ar }}</p>
                        @endif
                        
                        <hr>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Category:</strong></div>
                            <div class="col-6">
                                @if($medicine->category)
                                    <span class="badge badge-info">{{ $medicine->category->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Seller:</strong></div>
                            <div class="col-6">
                                @if($medicine->seller)
                                    {{ $medicine->seller->name_en }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Price:</strong></div>
                            <div class="col-6">
                                @if($medicine->discount_percentage > 0)
                                    <del class="text-muted">{{ number_format($medicine->price, 2) }} EGP</del>
                                    <br>
                                    <strong class="text-success">{{ number_format($medicine->final_price, 2) }} EGP</strong>
                                    <span class="badge badge-danger">-{{ $medicine->discount_percentage }}%</span>
                                @else
                                    <strong>{{ number_format($medicine->price, 2) }} EGP</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Rating:</strong></div>
                            <div class="col-6">
                                @if($medicine->rating)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-star"></i> {{ $medicine->rating }}
                                    </span>
                                @else
                                    <span class="text-muted">No rating</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Quantity:</strong></div>
                            <div class="col-6">{{ $medicine->quantity ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Title --}}
                @if($medicine->title_en || $medicine->title_ar)
                    <div class="mb-3">
                        <strong>Title:</strong><br>
                        @if($medicine->title_en)
                            <span class="badge badge-light">{{ $medicine->title_en }}</span>
                        @endif
                        @if($medicine->title_ar)
                            <span class="badge badge-light">{{ $medicine->title_ar }}</span>
                        @endif
                    </div>
                @endif

                {{-- Description --}}
                @if($medicine->description_en || $medicine->description_ar)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        @if($medicine->description_en)
                            <p class="mb-1">{{ $medicine->description_en }}</p>
                        @endif
                        @if($medicine->description_ar)
                            <p class="mb-1 text-right" dir="rtl">{{ $medicine->description_ar }}</p>
                        @endif
                    </div>
                @endif

                {{-- Product Details --}}
                <div class="row">
                    @if($medicine->product_type)
                        <div class="col-md-6 mb-2">
                            <strong>Product Type:</strong><br>
                            <span class="badge badge-secondary">{{ $medicine->product_type }}</span>
                        </div>
                    @endif

                    @if($medicine->manufacturer)
                        <div class="col-md-6 mb-2">
                            <strong>Manufacturer:</strong><br>
                            {{ $medicine->manufacturer }}
                        </div>
                    @endif

                    @if($medicine->weight)
                        <div class="col-md-6 mb-2">
                            <strong>Weight:</strong><br>
                            {{ $medicine->weight }}
                        </div>
                    @endif

                    @if($medicine->dimensions)
                        <div class="col-md-6 mb-2">
                            <strong>Dimensions:</strong><br>
                            {{ $medicine->dimensions }}
                        </div>
                    @endif
                </div>

                {{-- Animals --}}
                @if($medicine->animals->isNotEmpty())
                    <div class="mb-3">
                        <strong>Animals:</strong><br>
                        @foreach($medicine->animals as $animal)
                            <span class="badge badge-primary">{{ $animal->name }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Policies --}}
                @if($medicine->return_policy || $medicine->exchange_policy)
                    <hr>
                    @if($medicine->return_policy)
                        <div class="mb-2">
                            <strong>Return Policy:</strong>
                            <p class="mb-0">{{ $medicine->return_policy }}</p>
                        </div>
                    @endif
                    @if($medicine->exchange_policy)
                        <div>
                            <strong>Exchange Policy:</strong>
                            <p class="mb-0">{{ $medicine->exchange_policy }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" 
                    data-toggle="modal" data-target="#editMedicineModal{{ $medicine->id }}">
                    <i class="fas fa-edit"></i> Edit Product
                </button>
            </div>
        </div>
    </div>
</div>

