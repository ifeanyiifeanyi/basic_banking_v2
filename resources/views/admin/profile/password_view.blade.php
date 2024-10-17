@extends('admin.layouts.admin')

@section('title', "Update Password")

@section('css')

@endsection


@section('admin')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Password</h5>
                </div>

                <div class="card-body">
                    <x-alert-info />

                    <form id="passwordUpdateForm" action="{{ route('admin.password.update', auth()->user()) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Password Requirements:</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li id="lengthCheck" class="text-danger">
                                            <i class="fas fa-times"></i> At least 8 characters
                                        </li>
                                        <li id="uppercaseCheck" class="text-danger">
                                            <i class="fas fa-times"></i> At least one uppercase letter
                                        </li>
                                        <li id="lowercaseCheck" class="text-danger">
                                            <i class="fas fa-times"></i> At least one lowercase letter
                                        </li>
                                        <li id="numberCheck" class="text-danger">
                                            <i class="fas fa-times"></i> At least one number
                                        </li>
                                        <li id="specialCheck" class="text-danger">
                                            <i class="fas fa-times"></i> At least one special character
                                        </li>
                                        <li id="matchCheck" class="text-danger">
                                            <i class="fas fa-times"></i> Passwords match
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" id="submitButton" disabled>
                                Update Password
                            </button>
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
        const form = document.getElementById('passwordUpdateForm');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');
        const submitButton = document.getElementById('submitButton');
    
        // Password toggle buttons
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
    
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    
        // Real-time password validation
        function validatePassword() {
            const password = newPassword.value;
            const confirm = confirmPassword.value;
    
            const checks = {
                lengthCheck: password.length >= 8,
                uppercaseCheck: /[A-Z]/.test(password),
                lowercaseCheck: /[a-z]/.test(password),
                numberCheck: /\d/.test(password),
                specialCheck: /[^A-Za-z0-9]/.test(password),
                matchCheck: password === confirm && password !== ''
            };
    
            let allValid = true;
            Object.keys(checks).forEach(check => {
                const element = document.getElementById(check);
                const icon = element.querySelector('i');
                if (checks[check]) {
                    element.classList.remove('text-danger');
                    element.classList.add('text-success');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                } else {
                    element.classList.remove('text-success');
                    element.classList.add('text-danger');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                    allValid = false;
                }
            });
    
            submitButton.disabled = !allValid;
        }
    
        newPassword.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);
    
        // Form submission
        form.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPasswordValue = newPassword.value;
    
            if (currentPassword === newPasswordValue) {
                e.preventDefault();
                alert('New password must be different from current password');
            }
        });
    });
    </script>
@endsection