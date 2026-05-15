@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header">
        <h4><span class="text-muted fw-light">Profile /</span> Update Profile</h4>
        <p class="mb-0 text-muted">Edit your personal information</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-8 mx-auto animate-fade-in">
            <div class="card card-premium">
                <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="formAuthentication" action="{{ url('profile/update/' . $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="name" placeholder="Enter your username" value="{{ !empty($user->name) ? $user->name : old('name') }}" autofocus />
                                <span class="name-error text-danger" style="display:none;font-size:.8rem;"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ !empty($user->email) ? $user->email : old('email') }}" readonly />
                                <span class="email-error text-danger" style="display:none;font-size:.8rem;"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="image" name="image" />
                                <span class="image-error text-danger" style="display:none;font-size:.8rem;"></span>
                            </div>
                            @if (empty($user->id))
                            <div class="col-md-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter password" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                <span class="password-error text-danger" style="display:none;font-size:.8rem;"></span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-premium" type="submit">{{ !empty($user->id) ? 'Update Profile' : 'Create User' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(() => {
        $.validator.addMethod("filesize", function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        }, "File size must be less than {0}");
        $.validator.addMethod("noSpecialChars", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
        }, "Username must not contain special characters");
        $("#formAuthentication").validate({
            rules: {
                name: { required: true, minlength: 2, maxlength: 50, noSpecialChars: true },
                email: { required: true, email: true, maxlength: 50 },
                image: { required: false, extension: "jpg|jpeg|png|bmp", filesize: 5242880 },
                password: { required: function() { return $('#password').length > 0; }, minlength: 6 }
            },
            messages: {
                name: { required: "Please enter your username", minlength: "Username must be at least 2 characters", maxlength: "Username must be less than 50 characters", noSpecialChars: "Username must not contain special characters" },
                email: { required: "Please enter your email", email: "Please enter a valid email", maxlength: "Email must be less than 50 characters" },
                image: { extension: "Please upload only image files (jpg, jpeg, png, bmp)", filesize: "Image size must be less than 5 MB" },
                password: { required: "Please provide a password", minlength: "Password must be at least 6 characters" }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "name") element.next('.name-error').text(error.text()).show();
                else if (element.attr("name") === "email") element.next('.email-error').text(error.text()).show();
                else if (element.attr("name") === "image") element.next('.image-error').text(error.text()).show();
                else if (element.attr("name") === "password") element.next('.password-error').text(error.text()).show();
                else error.insertAfter(element);
            },
            success: function(label, element) {
                if ($(element).attr("name") === "name") $(element).next('.name-error').hide();
                else if ($(element).attr("name") === "email") $(element).next('.email-error').hide();
                else if ($(element).attr("name") === "image") $(element).next('.image-error').hide();
                else if ($(element).attr("name") === "password") $(element).next('.password-error').hide();
            },
            submitHandler: function(form) { form.submit(); }
        });
        $('#username, #email').on('input', function() {
            if ($(this).val().length > 50) $(this).val($(this).val().substring(0, 50));
        });
    });
});
</script>

