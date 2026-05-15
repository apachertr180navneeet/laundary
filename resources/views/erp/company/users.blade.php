@extends('erp.layouts.app')
@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users - {{ $company->name }}</h5>
                <a href="{{ route('users.create') }}?company_id={{ $company->id }}" class="btn btn-premium">
                    <i class="ti ti-plus me-1"></i>Add User
                </a>
            </div>
            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-premium alert-success mb-3">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-premium alert-danger mb-3">{{ Session::get('error') }}</div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $key => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $key }}</td>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile ?? '-' }}</td>
                                    <td>{{ $user->role_id == 1 ? 'Admin' : 'User' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No users found for this company</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
                <div class="mt-3">
                    <a href="{{ route('company.index') }}" class="btn btn-secondary">Back to Companies</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
