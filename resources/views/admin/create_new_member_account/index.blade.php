@extends('admin.layouts.admin')

@section('title', 'Create New Member')

@section('css')
    <style>
        .card-header {
            background-color: #f8f9fa;
        }

        .custom-switch {
            padding-left: 2.25rem;
        }

        .text-danger {
            color: #dc3545;
        }
    </style>
@endsection


@section('admin')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New User</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.create_new.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Personal Information --}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="mb-0">Personal Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="first_name"
                                                    class="form-control @error('first_name') is-invalid @enderror"
                                                    value="{{ old('first_name') }}" required>
                                                @error('first_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="last_name"
                                                    class="form-control @error('last_name') is-invalid @enderror"
                                                    value="{{ old('last_name') }}" required>
                                                @error('last_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Other Name</label>
                                                <input type="text" name="other_name"
                                                    class="form-control @error('other_name') is-invalid @enderror"
                                                    value="{{ old('other_name') }}">
                                                @error('other_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date" name="dob"
                                                    class="form-control @error('dob') is-invalid @enderror"
                                                    value="{{ old('dob') }}">
                                                @error('dob')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender"
                                                    class="form-control @error('gender') is-invalid @enderror">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="female"
                                                        {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other"
                                                        {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <input type="text" name="occupation"
                                                    class="form-control @error('occupation') is-invalid @enderror"
                                                    value="{{ old('occupation') }}">
                                                @error('occupation')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Profile Photo</label>
                                                <input type="file" name="photo"
                                                    class="form-control @error('photo') is-invalid @enderror"
                                                    accept="image/*">
                                                @error('photo')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contact Information --}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="mb-0">Contact Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone') }}">
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    value="{{ old('city') }}">
                                                @error('city')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" name="state"
                                                    class="form-control @error('state') is-invalid @enderror"
                                                    value="{{ old('state') }}">
                                                @error('state')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" name="country"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    value="{{ old('country') }}">
                                                @error('country')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>ZIP Code</label>
                                                <input type="text" name="zip"
                                                    class="form-control @error('zip') is-invalid @enderror"
                                                    value="{{ old('zip') }}">
                                                @error('zip')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Account Settings --}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="mb-0">Account Settings</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Role <span class="text-danger">*</span></label>
                                                <select name="role"
                                                    class="form-control @error('role') is-invalid @enderror" required>
                                                    <option value="member"
                                                        {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                        Admin</option>
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Account Status</label>
                                                <select name="account_status"
                                                    class="form-control @error('account_status') is-invalid @enderror">
                                                    <option value="1"
                                                        {{ old('account_status', '1') == '1' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="0"
                                                        {{ old('account_status') == '0' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                                @error('account_status')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Two-Factor Authentication</label>
                                                <select name="two_factor_enabled"
                                                    class="form-control @error('two_factor_enabled') is-invalid @enderror">
                                                    <option value="disabled"
                                                        {{ old('two_factor_enabled', 'disabled') == 'disabled' ? 'selected' : '' }}>
                                                        Disabled</option>
                                                    <option value="enabled"
                                                        {{ old('two_factor_enabled') == 'enabled' ? 'selected' : '' }}>
                                                        Enabled</option>
                                                </select>
                                                @error('two_factor_enabled')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="canTransfer"
                                                        name="can_transfer" value="1"
                                                        {{ old('can_transfer', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="canTransfer">Can Transfer
                                                        Funds</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="canReceive"
                                                        name="can_receive" value="1"
                                                        {{ old('can_receive', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="canReceive">Can Receive
                                                        Funds</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
