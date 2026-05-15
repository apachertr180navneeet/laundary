@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header">
        <h4><span class="text-muted fw-light">Users /</span> Add Admin</h4>
        <p class="mb-0 text-muted">Create a new admin user account</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8 animate-fade-in">
            <div class="card card-premium">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form id="formAuthentication" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="name" placeholder="Enter username" value="" autofocus />
                                <span class="text-danger" id="username_error" style="font-size:.8rem;">
                                    @error('name') {{ $message }} @enderror
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="" />
                                <span class="text-danger" id="email_error" style="font-size:.8rem;">
                                    @error('email') {{ $message }} @enderror
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label for="domain" class="form-label">Domain Name</label>
                                <input type="text" class="form-control" id="domain" name="domain" placeholder="Enter domain name" value="" />
                                <span class="text-danger" id="domain_error" style="font-size:.8rem;">
                                    @error('domain') {{ $message }} @enderror
                                </span>
                            </div>
                            <div class="col-md-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter password" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                <label id="password-error" class="error" for="password"></label>
                                <span class="text-danger" id="password_error" style="font-size:.8rem;">
                                    @error('password') {{ $message }} @enderror
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="starting_date">Starting Date</label>
                                <input type="date" name="starting_date" class="form-control date-input" id="starting_date" />
                                <span class="text-danger" id="starting_date_error" style="font-size:.8rem;">
                                    @error('starting_date') {{ $message }} @enderror
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control date-input" id="end_date" />
                                <span class="text-danger" id="end_date_error" style="font-size:.8rem;">
                                    @error('end_date') {{ $message }} @enderror
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-premium" type="submit">
                                <i class="ti ti-device-floppy me-1"></i>Save
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-premium">Cancel</a>
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
    $(document).ready(function() {
        $('#username').on('input', function() {
            var name = $('#username').val().trim();
            if (name.length >= 50) $(this).attr('maxlength', 50);
            else $(this).removeAttr('maxlength');
            if (name && (name.length > 50)) $('#username_error').empty();
        });
        $.validator.addMethod("noSpecialChars", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
        }, "Username must not contain special characters");
        $.validator.addMethod("greaterThan", function(value, element, params) {
            return new Date(value) > new Date($('#starting_date').val());
        }, "End date must be greater than start date");
        $.validator.addMethod("noSpaces", function(value, element) {
            return this.optional(element) || /^[^\s].*$/.test(value);
        }, "Username must not contain spaces");
        $.validator.addMethod("minBookingDate", function(value, element) {
            var d = new Date(value); d.setHours(0,0,0,0);
            return d >= new Date(new Date().toDateString());
        }, "Date cannot be earlier than today.");
        $.validator.addMethod("yearOnlyFourDigits", function(value, element) {
            return value.split('-')[0].length === 4;
        }, "Year must be exactly four digits");
        $("#formAuthentication").validate({
            rules: {
                name: { required: true, minlength: 2, maxlength: 50, noSpecialChars: true, noSpaces: true },
                email: { required: true, email: true, maxlength: 50 },
                domain: { required: true, maxlength: 50, noSpaces: true },
                password: { required: true },
                starting_date: { required: true, date: true, minBookingDate: true, yearOnlyFourDigits: true },
                end_date: { required: true, date: true, greaterThan: true, yearOnlyFourDigits: true }
            },
            messages: {
                name: { required: "Please enter username", minlength: "Minimum 2 characters", maxlength: "Maximum 50 characters", noSpecialChars: "No special characters", noSpaces: "No spaces" },
                email: { required: "Please enter email", email: "Invalid email", maxlength: "Maximum 50 characters" },
                domain: { required: "Please enter domain", maxlength: "Maximum 50 characters", noSpaces: "No spaces" },
                password: { required: "Please enter password" },
                starting_date: { required: "Please enter starting date", date: "Invalid date", yearOnlyFourDigits: "Year must be 4 digits" },
                end_date: { required: "Please enter end date", date: "Invalid date", yearOnlyFourDigits: "Year must be 4 digits" }
            },
            errorPlacement: function(error, element) {
                var map = { name: '#username_error', email: '#email_error', domain: '#domain_error', password: '#password_error', starting_date: '#starting_date_error', end_date: '#end_date_error' };
                var target = map[element.attr("name")];
                if (target) $(target).text(error.text());
                else error.insertAfter(element);
            },
            success: function(label, element) {
                var map = { name: '#username_error', email: '#email_error', domain: '#domain_error', password: '#password_error', starting_date: '#starting_date_error', end_date: '#end_date_error' };
                var target = map[$(element).attr("name")];
                if (target) $(target).empty();
            },
            submitHandler: function(form) { form.submit(); }
        });
        $(document).on('input', '#username, #email, #domain', function() {
            if ($(this).val().length > 50) $(this).val($(this).val().substring(0, 50));
        });
    });
});
</script>

