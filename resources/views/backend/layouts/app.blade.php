<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../..///theam/assets/" data-template="vertical-menu-template-starter">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Laundry ERP</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{url('/theam/assets/img/favicon/favicon.ico')}}" />
    @include('backend.layouts.include.style')
    @yield('extrastyle')
    <script src="{{url('/theam/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{url('/theam/assets/js/config.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('backend.layouts.include.sidebar')
            <div class="layout-page">
                <nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                                <i class="ti ti-sm"></i>
                            </a>
                        </div>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->image ? asset('images/'.Auth::user()->image) : asset('theam/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" style="width:38px;height:38px;object-fit:cover;" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ Auth::user()->image ? asset('images/'.Auth::user()->image) : asset('theam/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" style="width:38px;height:38px;object-fit:cover;" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 mt-2">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->name ?? '' }}</span>
                                                    <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('myProfile') }}">
                                            <i class="ti ti-user-check me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('change.password') }}">
                                            <i class="ti ti-lock me-2"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="ti ti-logout me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    @include('backend.layouts.include.footer')
    @include('backend.layouts.include.js')
    @yield('extrascript')
</body>
</html>
