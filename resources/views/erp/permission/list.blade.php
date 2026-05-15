@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4>Permissions</h4>
            <p class="mb-0 text-muted">View all system permissions</p>
        </div>
        <a href="{{route('permission.add')}}" class="btn btn-premium">
            <i class="ti ti-plus me-1"></i>Create
        </a>
    </div>
    <div class="card animate-fade-in card-premium">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table display no-wrap table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Assign To</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($list))
                        @foreach ($list as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row->name }}</td>
                            <td><span class="badge bg-label-primary">{{ $row->guard_name }}</span></td>
                            <td>{{ $row->created_at }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

