@extends('backend.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-5">
    <div class="content-wrapper mt-5">
        <div class="row">
            <div class="col-md-11"></div>
            <div class="col-md-1">
            <a href="{{route('tenants.create')}}" class="btn btn-primary mb-2">Create</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                <div class="card container-fluid">
                    <h5 class="card-header">Users List</h5>
                    <div class="table-responsive text-nowrap">
                        <table id="myTable" class="table display no-wrap table-hover user_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($tenants as $tenant )
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $tenant->name }}</td>
                                    <td class="px-6 py-4">{{ $tenant->email }}</td>
                                    <td class="px-6 py-4">{{ $tenant->id }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
