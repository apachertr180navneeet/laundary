@extends('backend.layouts.auth_app')
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
                    <h4>Welcome Back! 👋</h4>
                    <p>Sign in to your account to continue</p>
                </div>
                @if (Session::has('error'))
                    <div class="alert alert-premium alert-danger mb-3">{{ Session::get('error') }}</div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-premium alert-success mb-3">{{ Session::get('success') }}</div>
                @endif
                <form id="formAuthentication" action="{{route('login')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Enter your email or username" value="{{old('email')}}" autofocus />
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label" for="password">Password</label>
                            <a href="{{route('password.request')}}" style="font-size:.8rem;font-weight:600;color:var(--pre-primary);">Forgot Password?</a>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Enter your password" aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                        @if ($errors->any())
                            <span class="text-danger" style="font-size:.8rem;margin-top:.25rem;display:block;">{{ $errors->first() }}</span>
                        @endif
                    </div>
                    <button class="btn btn-premium w-100" type="submit">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = passwordInput.nextElementSibling.querySelector("i");
    eyeIcon.addEventListener("click", function() {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.classList.toggle("ti-eye-off");
        this.classList.toggle("ti-eye");
    });
});
</script>
