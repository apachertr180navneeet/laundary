@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4>Admin Users</h4>
            <p class="mb-0 text-muted">Manage all registered admin users</p>
        </div>
        <a href="{{route('users.create')}}" class="btn btn-premium">
            <i class="ti ti-plus me-1"></i>Create
        </a>
    </div>
    <div class="card animate-fade-in card-premium">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table display no-wrap table-hover user_datatable" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Domain</th>
                            <th width="120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->domains as $domain)
                                    {{ $domain->domain }}{{ $loop->last ? '':',' }}
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="#" class="btn btn-premium-sm btn-premium">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

