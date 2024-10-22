@extends('members.layouts.member')

@section('title', 'My Profile')

@section('member')
    <div class="container-fluid">
      

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="dropdown float-end">
                                <a class="text-body dropdown-toggle font-size-18" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                    <i class="uil uil-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#"
                                        onclick="document.getElementById('profile-photo-input').click()">Change Photo</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <img src="{{ $user->photo }}" alt=""
                                    class="avatar-lg rounded-circle img-thumbnail">
                            </div>
                            <h5 class="mt-3 mb-1">{{ $user->full_name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <div class="pt-1 mt-3">
                            <h5 class="mb-3 font-size-15">Account Info</h5>
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless text-muted">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Phone</th>
                                            <td>{{ $user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Location</th>
                                            <td>{{ $user->city }}, {{ $user->state }}, {{ $user->country }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Joining Date</th>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4 card-title">Edit Profile</h4>

                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="profile-photo-input" name="photo" class="d-none" accept="image/*">

                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ old('last_name', $user->last_name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Other Name</label>
                                    <input type="text" class="form-control" name="other_name"
                                        value="{{ old('other_name', $user->other_name) }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" class="form-control" name="occupation"
                                        value="{{ old('occupation', $user->occupation) }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ old('city', $user->city) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state"
                                        value="{{ old('state', $user->state) }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country"
                                        value="{{ old('country', $user->country) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ old('zip', $user->zip) }}" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4 card-title">Security Settings</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label d-block">Two Factor Authentication</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="twoFactorSwitch"
                                            {{ $user->two_factor_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="twoFactorSwitch">Enable/Disable</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
