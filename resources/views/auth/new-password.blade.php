@extends('backend.layouts.auth_app')
@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 ">
                <!-- Forgot Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4">
                            <a href="javascript:void(0);" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0"/>
                                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"/>
                                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0"/>
                                    </svg>
                                </span>
                                <span class="app-brand-text demo menu-text fw-bold" style="text-transform:none;">Laundry ERP</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2 text_14355a_hp">Reset Password? 🔒</h4>
                        <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                        @if (Session::has('error'))
                            <span class="text-danger">{{ Session::get('error') }}</span>
                        @endif
                        <form id="formAuthentication" class="mb-3" action="{{ route('store.new.password') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="token" value="{{ $data['id'] }}" />
                            {{-- <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label> --}}
                            {{-- <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter your password" value="" autofocus /> --}}
                            {{-- <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div> --}}
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="password">New Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="new_password" class="form-control" name="new_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" value="" autofocus />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>

                            </div>
                            <div class="mb-3">
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label" for="password">Confirm Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password"  class="form-control" id="confirm_password" name="confirm_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" value="" autofocus/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>

                            </div>

                            <button class="btn btn_1F446E_hp w-100">Save</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}"
                                class="d-flex align-items-center justify-content-center text_14355a_hp">
                                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const togglePasswordVisibility = (inputId, icon) => {
            const input = document.getElementById(inputId);
            icon.addEventListener("click", function() {
                const type = input.getAttribute("type") === "password" ? "text" : "password";
                input.setAttribute("type", type);
                this.classList.toggle("ti-eye-off");
                this.classList.toggle("ti-eye");
            });
        };

        const newPasswordIcon = document.querySelector("#new_password").nextElementSibling.querySelector("i");
        const confirmPasswordIcon = document.querySelector("#confirm_password").nextElementSibling.querySelector("i");

        togglePasswordVisibility("new_password", newPasswordIcon);
        togglePasswordVisibility("confirm_password", confirmPasswordIcon);
    });
</script>
