@extends('backend.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4>Roles</h4>
            <p class="mb-0 text-muted">Manage user roles and permissions</p>
        </div>
        <a href="{{route('role.add')}}" class="btn btn-premium">
            <i class="ti ti-plus me-1"></i>Create
        </a>
    </div>
    <div class="card animate-fade-in card-premium">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table display no-wrap table-hover role_datatable" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th width="120px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extrascript')
<script type="text/javascript">
    $(function() {
        $('.role_datatable').DataTable({
            processing: false,
            serverSide: true,
            scrollX: true,
            fixedHeader: { header: true, footer: true },
            stateSave: true,
            ajax: "",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            bLengthChange: false
        });
    });
</script>
@endsection
