@extends('admin.layouts.admin')

@section('title', '2FA')
@section('admin')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Two-Factor Authentication</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($user->two_factor_enabled === 'disabled')
                            <h5 class="mb-3">Enable Two-Factor Authentication</h5>
                            <p class="mb-3">Follow these steps to enable 2FA:</p>

                            <div class="mb-4">
                                <p>1. Install an authenticator app like Google Authenticator on your phone.</p>
                                <p>2. Scan the QR code below or manually enter the secret key.</p>
                                <p>3. Enter the 6-digit code from your authenticator app to verify.</p>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 text-center">
                                    <!-- QR Code will be rendered here -->
                                    <div id="qrcode"></div>
                                </div>
                                <div class="col-md-6">
                                    <p>Manual entry secret key:</p>
                                    <code class="user-select-all">{{ $secretKey }}</code>
                                </div>
                            </div>

                            <form action="{{ route('profile.2fa.enable') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="code" class="form-label">Verification Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Enable 2FA</button>
                            </form>
                        @else
                            <h5 class="mb-3">Two-Factor Authentication is Enabled</h5>
                            <p>Your account is secure with 2FA. Recovery codes are provided below in case you lose access to
                                your authenticator app.</p>

                            <div class="mb-4">
                                <h6>Recovery Codes</h6>
                                <p class="text-warning">Save these codes in a secure place. Each code can only be used once.
                                </p>
                                <div class="row">
                                    @foreach ($recoveryCodes as $code)
                                        <div class="col-md-6">
                                            <code class="user-select-all">{{ $code }}</code>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <form action="{{ route('profile.2fa.disable') }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.');">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="code" class="form-label">2FA Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger">Disable 2FA</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script src="/assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrcodeContainer = document.getElementById('qrcode');
            if (qrcodeContainer) {
                new QRCode(qrcodeContainer, {
                    text: @json($qrCodeData),
                    width: 200,
                    height: 200
                });
            }
        });
    </script>
@endsection
