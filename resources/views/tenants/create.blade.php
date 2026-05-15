@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-6">
                            <h5 class="card-header">Add Admin</h5>
                        </div>
                        <div class="card-body">
                            <form id="formAuthentication" class="mb-3" action="{{ route('tenants.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="name"
                                            placeholder="Enter your username" value="" autofocus />
                                        <span class="text-danger" id="username_error">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Enter your email" value="" />
                                        <span class="text-danger" id="email_error">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3 form-password-toggle">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" />
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </div>
                                        <label id="password-error" class="error" for="password"></label>
                                        <span class="text-danger" id="password_error">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span>
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
