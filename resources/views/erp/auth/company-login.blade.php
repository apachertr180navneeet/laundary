@extends('erp.layouts.auth_app')
@section('content')
<div class="auth-wrapper-premium">
    <div class="auth-card-premium">
        <div class="card">
            <div class="card-body">
                <div class="auth-logo">
                    <h4 class="mb-0" style="color:var(--pre-primary);">{{ $company->name }}</h4>
                </div>
                <div class="auth-title mt-3">
                    <h4>Welcome Back! 👋</h4>
                    <p>Sign in to <strong>{{ $company->name }}</strong> account</p>
                </div>
                @if (Session::has('error'))
                    <div class="alert alert-premium alert-danger mb-3">{{ Session::get('error') }}</div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-premium alert-success mb-3">{{ Session::get('success') }}</div>
                @endif
                <form id="formAuthentication" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Enter your email or username" value="{{ old('email') }}" autofocus />
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label" for="password">Password</label>
                            <a href="{{ route('password.request') }}" style="font-size:.8rem;font-weight:600;color:var(--pre-primary);">Forgot Password?</a>
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
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" style="font-size:.85rem;color:var(--pre-primary);">Back to main login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extrascript')
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
@endsection
