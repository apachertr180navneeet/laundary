@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="page-header">
        <h4><span class="text-muted fw-light">Roles /</span> {{ !empty($role->id) ? 'Edit' : 'Add' }} Role</h4>
        <p class="mb-0 text-muted">{{ !empty($role->id) ? 'Modify' : 'Create a new' }} role and assign permissions</p>
    </div>
    <div class="card animate-fade-in card-premium">
        <div class="card-body">
            <form class="needs-validation" method="post" action="{{ !empty($role->id) ? route('role.edit.post',['id' => $role->id]) : route('role.post') }}" novalidate>
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">Role Name</label>
                        <input type="text" class="form-control" name="name" id="validationCustom01" placeholder="Enter role name" required value="{{ !empty($role->name) ? $role->name : '' }}">
                        <span class="text-danger" style="font-size:.8rem;">
                            @error('name') {{ $message }} @enderror
                        </span>
                    </div>
                </div>
                <h5 class="mb-3">Permissions</h5>
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="checkAll" name="" value="">
                    <label class="form-check-label fw-semibold" for="checkAll">Select All</label>
                </div>
                <div class="row g-4">
                    @if (!empty($curruntopeningdata[0]))
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Current Openings</h6>
                                @foreach ($curruntopeningdata as $value)
                                <div class="form-check mb-2">
                                    <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                    <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (!empty($userdata[0]))
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">User</h6>
                                @foreach ($userdata as $value)
                                <div class="form-check mb-2">
                                    <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                    <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (!empty($roledata[0]))
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Role</h6>
                                @foreach ($roledata as $value)
                                <div class="form-check mb-2">
                                    <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                    <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <button class="btn btn-premium mt-4" type="submit">{{ !empty($role->id) ? 'Update' : 'Add' }} Role</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('extrascript')
<script>
    $('#checkAll').on('click', function() {
        $('.models_checkbox').each(function() { this.checked = this.checked; });
        $('.models_checkbox').each(function() { this.checked = $('#checkAll').prop('checked'); });
    });
</script>
@endsection

