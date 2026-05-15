@if (!Auth::user())
    @php
        $errorMessage = 'Please contact the superadmin for access.';
        return redirect()->route('login')->withErrors([$errorMessage]);
    @endphp
@else
@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @php
        $loginname = Auth::user()->name;
        $loginemail = Auth::user()->email;
        $loginmobile = Auth::user()->mobile;
    @endphp
    <div class="page-header">
        <h4>My Profile</h4>
        <p class="mb-0 text-muted">View your account information</p>
    </div>
    <div class="row g-4">
        <div class="col-xl-4 animate-fade-in">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-xl mb-3">
                        <img src="{{ Auth::user()->image ? asset('images/'.Auth::user()->image) : asset('theam/assets/img/avatars/1.png') }}" alt class="rounded-circle" style="width:100px;height:100px;object-fit:cover;border:3px solid var(--pre-primary);" />
                    </div>
                    <h5 class="mb-1">{{ $loginname }}</h5>
                    <p class="text-muted mb-3">{{ $loginemail }}</p>
                    <a href="{{ route('edit.profile', ['id' => Auth::user()->id]) }}" class="btn btn-premium w-100">
                        <i class="ti ti-edit me-1"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-8 animate-fade-in-delay-1">
            <div class="card card-premium">
                <div class="card-header">
                    <h5 class="mb-0">Account Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <p class="fw-semibold mb-0" style="font-size:1.05rem;">{{ $loginname }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <p class="fw-semibold mb-0" style="font-size:1.05rem;">{{ $loginemail }}</p>
                        </div>
                        @if($loginmobile)
                        <div class="col-md-6">
                            <label class="form-label">Mobile</label>
                            <p class="fw-semibold mb-0" style="font-size:1.05rem;">{{ $loginmobile }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Member Since</label>
                            <p class="fw-semibold mb-0" style="font-size:1.05rem;">{{ Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endif

