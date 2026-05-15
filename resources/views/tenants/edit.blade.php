@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-6">
                            <h5 class="card-header">Edit Admin</h5>
                        </div>
                        <div class="card-body">
                            <form id="formAuthentication" class="mb-3" action="{{ route('tenants.update', $tenents->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control name" id="username" name="name"
                                            value="{{ $tenents->name }}" />
                                        <span class="name-error text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control email" id="email" name="email"
                                            value="{{ $tenents->email }}" />
                                        <span class="email-error text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="active" class="form-label">Is Activate</label>
                                        <select name="active" id="active" class="form-select active-select">
                                            <option value="1" {{ $tenents->is_active == 1 ? 'selected' : '' }}>
                                                Activate</option>
                                            <option value="0" {{ $tenents->is_active == 0 ? 'selected' : '' }}>
                                                Deactivate</option>
                                        </select>
                                        <span class="active-error text-danger" style="display:none;"> </span>
                                    </div>
                                    <div class="Add_order_btn_area text-end mb-2">
                                        <button class="btn btn_1F446E_hp">Save</button>
                                        <a href="{{ route('tenants.index') }}" class="btn btn_1F446E_hp">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).ready(function() {
            $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
            }, "Username must not contain special characters");
            $.validator.addMethod("noSpaces", function(value, element) {
                return this.optional(element) || /^[^\s].*$/.test(value);
            }, "Username must not contain spaces");

            $("#formAuthentication").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 50,
                        noSpecialChars: true,
                        noSpaces: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    active: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the username",
                        maxlength: "Username must be less than 50 characters",
                        noSpecialChars: "Username must not contain special characters",
                        noSpaces: "Username must not contain spaces"
                    },
                    email: {
                        required: "Please enter the email",
                        email: "Please enter a valid email address"
                    },
                    active: {
                        required: "Please select activation status"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === "name") {
                        element.next('.name-error').text(error.text()).show();
                    } else if (element.attr("name") === "email") {
                        element.next('.email-error').text(error.text()).show();
                    } else if (element.attr("name") === "active") {
                        element.next('.active-error').text(error.text()).show();
                    } else {
                        error.insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("name") === "name") {
                        $(element).next('.name-error').hide();
                    } else if ($(element).attr("name") === "email") {
                        $(element).next('.email-error').hide();
                    } else if ($(element).attr("name") === "active") {
                        $(element).next('.active-error').hide();
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let valid = true;

                    $("#formAuthentication").find('select.active-select').each(function() {
                        if ($(this).val() === "") {
                            $(this).next('.active-error').text(
                                "Please select activation status").show();
                            valid = false;
                        } else {
                            $(this).next('.active-error').hide();
                        }
                    });

                    if (valid) {
                        form.submit();
                    } else {
                        alert("Please correct the errors in the form.");
                    }
                }
            });

            $(document).on('focusout', '.name', function() {
                $(this).valid();
            });

            $(document).on('focusout', '.email', function() {
                $(this).valid();
            });

            $(document).on('change', '.active-select', function() {
                $(this).valid();
            });

            $(document).on('input', '.name, .email', function() {
                if ($(this).val().length > 50) {
                    $(this).val($(this).val().substring(0, 50));
                }
            });
        });
    });
</script>
