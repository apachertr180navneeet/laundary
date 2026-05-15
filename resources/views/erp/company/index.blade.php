@extends('erp.layouts.app')
@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Company Master</h5>
                <button type="button" class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                    <i class="ti ti-plus me-1"></i>Add Company
                </button>
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
                                <th>Company Name</th>
                                <th>Users</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>GST No</th>
                                <th>Status</th>
                                <th>Login URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $key => $company)
                                <tr>
                                    <td>{{ $companies->firstItem() + $key }}</td>
                                    <td><strong>{{ $company->name }}</strong></td>
                                    <td><span class="badge bg-label-info">{{ $company->users_count }}</span></td>
                                    <td>{{ $company->email ?? '-' }}</td>
                                    <td>{{ $company->phone ?? '-' }}</td>
                                    <td>{{ $company->city ?? '-' }}</td>
                                    <td>{{ $company->gst_number ?? '-' }}</td>
                                    <td>
                                        @if ($company->status)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('company.login', $company->slug) }}" target="_blank" style="font-size:.75rem;">
                                            {{ url("/erp/login/{$company->slug}") }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('company.users', $company->id) }}" class="btn btn-sm btn-info" title="View Users">
                                            <i class="ti ti-user"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary edit-company"
                                            data-id="{{ $company->id }}"
                                            data-name="{{ $company->name }}"
                                            data-email="{{ $company->email }}"
                                            data-phone="{{ $company->phone }}"
                                            data-address="{{ $company->address }}"
                                            data-city="{{ $company->city }}"
                                            data-state="{{ $company->state }}"
                                            data-gst_number="{{ $company->gst_number }}"
                                            data-status="{{ $company->status }}"
                                            data-bs-toggle="modal" data-bs-target="#editCompanyModal">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-company"
                                            data-id="{{ $company->id }}" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No companies found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Company Modal --}}
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('company.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-premium">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Company Modal --}}
<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editCompanyForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_number" id="edit_gst_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" id="edit_city" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" id="edit_state" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-premium">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this company?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCompany">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extrascript')
<script>
$(document).ready(function() {
    // Edit - populate modal
    $(document).on('click', '.edit-company', function() {
        var id = $(this).data('id');
        $('#edit_name').val($(this).data('name'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_phone').val($(this).data('phone'));
        $('#edit_address').val($(this).data('address'));
        $('#edit_city').val($(this).data('city'));
        $('#edit_state').val($(this).data('state'));
        $('#edit_gst_number').val($(this).data('gst_number'));
        $('#edit_status').val($(this).data('status'));
        $('#editCompanyForm').attr('action', '{{ url("erp/company") }}/' + id);
    });

    // Delete
    var deleteId = null;
    $(document).on('click', '.delete-company', function() {
        deleteId = $(this).data('id');
    });

    $('#confirmDeleteCompany').on('click', function() {
        if (deleteId) {
            $.ajax({
                url: '{{ url("erp/company") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting company');
                }
            });
        }
    });
});
</script>
@endsection
