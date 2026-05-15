@if (!Auth::user())
    @php
        $errorMessage = 'Please contact the superadmin for access.';
        return redirect()->route('login')->withErrors([$errorMessage]);
    @endphp
@else
@extends('backend.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header">
        <h4><span class="text-muted fw-light">Security /</span> Change Password</h4>
        <p class="mb-0 text-muted">Update your account password</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-6 animate-fade-in">
            <div class="card card-premium">
                <div class="card-header">
                    <h5 class="mb-0">Reset Password 🔒</h5>
                    <small class="text-muted">for <span class="fw-bold">{{ Auth::user()->email ?? '' }}</span></small>
                </div>
                <div class="card-body">
                    <form id="formAuthentication" action="{{ route('change.password.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="password">Old Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control password" name="password"
                                    placeholder="Enter old password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @if ($errors->has('password'))
                                <span class="password-error text-danger" style="font-size:.8rem;">{{ $errors->first('password') }}</span>
                            @else
                                <span class="password-error text-danger" style="display:none;font-size:.8rem;"></span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="newpassword">New Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="newpassword" class="form-control newpassword" name="new_password"
                                    placeholder="Enter new password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @if ($errors->has('new_password'))
                                <span class="newpassword-error text-danger" style="font-size:.8rem;">{{ $errors->first('new_password') }}</span>
                            @else
                                <span class="newpassword-error text-danger" style="display:none;font-size:.8rem;"></span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="confirmpassword">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="confirmpassword" class="form-control confirmpassword" name="confirm_password"
                                    placeholder="Confirm new password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @if ($errors->has('confirm_password'))
                                <span class="confirmpassword-error text-danger" style="font-size:.8rem;">{{ $errors->first('confirm_password') }}</span>
                            @else
                                <span class="confirmpassword-error text-danger" style="display:none;font-size:.8rem;"></span>
                            @endif
                        </div>
                        <button class="btn btn-premium w-100" type="submit">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {
        $.validator.addMethod("notSamePasswords", function(value, element) {
            return $('#password').val() !== $('#newpassword').val();
        }, "Old and new password should not be the same");
        $("#formAuthentication").validate({
            rules: {
                password: { required: true, minlength: 6, notSamePasswords: true },
                new_password: { required: true, minlength: 6, notSamePasswords: true },
                confirm_password: { required: true, minlength: 6, equalTo: "#newpassword" }
            },
            messages: {
                password: { required: "Please provide your old password", minlength: "Password must be at least 6 characters", notSamePasswords: "Old and new password should not be the same" },
                new_password: { required: "Please provide a new password", minlength: "New password must be at least 6 characters", notSamePasswords: "Old and new password should not be the same" },
                confirm_password: { required: "Please confirm your new password", minlength: "Confirm password must be at least 6 characters", equalTo: "Passwords must match" }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "password") element.closest('.mb-3').find('.password-error').text(error.text()).show();
                else if (element.attr("name") === "new_password") element.closest('.mb-3').find('.newpassword-error').text(error.text()).show();
                else if (element.attr("name") === "confirm_password") element.closest('.mb-3').find('.confirmpassword-error').text(error.text()).show();
                else error.insertAfter(element);
            },
            success: function(label, element) {
                if ($(element).attr("name") === "password") $(element).closest('.mb-3').find('.password-error').hide();
                else if ($(element).attr("name") === "new_password") $(element).closest('.mb-3').find('.newpassword-error').hide();
                else if ($(element).attr("name") === "confirm_password") $(element).closest('.mb-3').find('.confirmpassword-error').hide();
            },
            submitHandler: function(form) { form.submit(); }
        });
        $(document).on('input', '.password, .newpassword, .confirmpassword', function() {
            if ($(this).val().length > 50) $(this).val($(this).val().substring(0, 50));
        });
    });
});
</script>
@endif
