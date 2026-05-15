@extends('backend.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header">
        <h4><span class="text-muted fw-light">Permissions /</span> Add Permission</h4>
        <p class="mb-0 text-muted">Create a new system permission</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-6 animate-fade-in">
            <div class="card card-premium">
                <div class="card-header">
                    <h5 class="mb-0">Permission Details</h5>
                </div>
                <div class="card-body">
                    <form class="needs-validation" method="post" action="{{ route('permission.post') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="validationCustom01">Permission Name</label>
                            <input type="text" class="form-control" name="permission" id="validationCustom01" placeholder="Enter permission name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="validationCustom21">Type ID</label>
                            <input type="text" class="form-control" name="type_id" id="validationCustom21" placeholder="Enter type ID" required>
                        </div>
                        <button class="btn btn-premium" type="submit">
                            <i class="ti ti-device-floppy me-1"></i>Add Permission
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
