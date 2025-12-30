<div class="modal fade" id="createuserModal" tabindex="-1" aria-labelledby="createuserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('dashboard.users.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Name --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Family Name</label>
                            <input type="text" name="family_name" class="form-control" value="{{ old('family_name') }}">
                        </div>
                    </div>

                    {{-- Email & Phone --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    {{-- Age & Gender --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control" value="{{ old('age') }}" min="1" max="120">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    {{-- City & Address --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <select name="city_id" class="form-control">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                    </div>

                    {{-- Status Checkboxes --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_verified" class="form-check-input" id="createIsVerified" 
                                    value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="createIsVerified">
                                    Verified
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_provider" class="form-check-input" id="createIsProvider" 
                                    value="1" {{ old('is_provider') ? 'checked' : '' }}>
                                <label class="form-check-label" for="createIsProvider">
                                    Provider
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </form>
    </div>
</div>

