@extends('erp.layouts.auth_app')
@section('content')
<div class="auth-wrapper-premium">
    <div class="auth-card-premium">
        <div class="card">
            <div class="card-body">
                <div class="auth-logo">
                    <svg width="36" height="24" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#1F446E"/>
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"/>
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#1F446E"/>
                    </svg>
                    <span>Laundry ERP</span>
                </div>
                <div class="auth-title">
                    <h4>Reset Password 🔒</h4>
                    <p>Enter your new password below</p>
                </div>
                <form action="{{ route('reset.password.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address</label>
                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="text-danger" style="font-size:.8rem;">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password" required>
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-danger" style="font-size:.8rem;">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required>
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger" style="font-size:.8rem;">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-premium w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.form-password-toggle .input-group-text').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('input');
        const icon = this.querySelector('i');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('ti-eye-off');
        icon.classList.toggle('ti-eye');
    });
});
</script>
@endsection

