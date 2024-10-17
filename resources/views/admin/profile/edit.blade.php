@extends('admin.layouts.admin')

@section('title', 'Edit Account')

@section('css')

@endsection


@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Admin Profile</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.profile.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Personal Information -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-4">Personal Information</h6>
                                <hr>
                                <div class="row g-3">
                                    <div class="col-md-4 mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name"
                                            value="{{ old('first_name', $user->first_name) }}">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name"
                                            value="{{ old('last_name', $user->last_name) }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="other_name" class="form-label">Other Name</label>
                                        <input type="text" class="form-control @error('other_name') is-invalid @enderror"
                                            id="other_name" name="other_name"
                                            value="{{ old('other_name', $user->other_name) }}">
                                        @error('other_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username', $user->username) }}">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-4">Contact Information</h6>
                                <hr>
                                <div class="row g-3">
                                    <div class="col-md-6 mb-4">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $user->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $user->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            id="state" name="state" value="{{ old('state', $user->state) }}">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="zip" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                            id="zip" name="zip" value="{{ old('zip', $user->zip) }}">
                                        @error('zip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                                            id="country" name="country" value="{{ old('country', $user->country) }}">
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="mb-4">
                                <h6 class="fw-bold  mb-4">Additional Information</h6>
                                <hr>
                                <div class="row g-3">
                                    <div class="col-md-6 mb-4">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                            id="dob" name="dob"
                                            value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                                        @error('dob')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">Choose...</option>
                                            <option value="Male"
                                                {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female"
                                                {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="Other"
                                                {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="occupation" class="form-label">Occupation</label>
                                        <input type="text"
                                            class="form-control @error('occupation') is-invalid @enderror"
                                            id="occupation" name="occupation"
                                            value="{{ old('occupation', $user->occupation) }}">
                                        @error('occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Photo -->
                            <div class="mb-4">
                                <h6 class="fw-bold">Profile Photo</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if ($user->photo)
                                                        <img src="{{ $user->photo }}" alt="Current profile photo"
                                                            id="currentProfileImage" class="img-thumbnail"
                                                            style="max-width: 150px; height: auto;">
                                                    @else
                                                        <div id="currentProfileImage"
                                                            class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                                            style="width: 150px; height: 150px;">
                                                            No Image
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div id="imagePreviewContainer" style="display: none;">
                                                        <img id="imagePreview" class="img-thumbnail"
                                                            style="max-width: 150px; height: auto;" alt="Image preview">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="photo" class="form-label">Choose new photo</label>
                                            <input type="file"
                                                class="form-control @error('photo') is-invalid @enderror" id="photo"
                                                name="photo" accept="image/*">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF.
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removePhotoBtn"
                                            style="display: none;">
                                            Remove New Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="d-flex justify-content-between m-5">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="{{ route('admin.profile') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


@section('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const currentProfileImage = document.getElementById('currentProfileImage');
            const removePhotoBtn = document.getElementById('removePhotoBtn');

            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Show remove button
                    removePhotoBtn.style.display = 'block';

                    // Hide current profile image and show preview
                    currentProfileImage.style.display = 'none';
                    imagePreviewContainer.style.display = 'block';

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    resetPreview();
                }
            });

            removePhotoBtn.addEventListener('click', function() {
                resetPreview();
                photoInput.value = ''; // Clear the file input
            });

            function resetPreview() {
                // Hide preview and remove button, show current profile image
                imagePreviewContainer.style.display = 'none';
                removePhotoBtn.style.display = 'none';
                currentProfileImage.style.display = 'block';
            }

            // Optional: Add file size validation
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.size > 2 * 1024 * 1024) { // 2MB
                    alert('File is too large. Maximum size is 2MB.');
                    this.value = ''; // Clear the file input
                    resetPreview();
                }
            });
        });
    </script>
@endsection
