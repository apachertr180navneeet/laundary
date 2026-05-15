@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (session('success'))
        <div class="alert alert-premium alert-success animate-fade-in">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-premium alert-danger animate-fade-in">{{ session('error') }}</div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Clients</h4>
            <p class="mb-0 text-muted">Manage your laundry clients</p>
        </div>
        <span class="badge bg-label-primary p-2 px-3">
            <i class="ti ti-users me-1"></i> {{ $clients->total() }} clients
        </span>
    </div>

    {{-- Add Client Form --}}
    <div class="card card-premium animate-fade-in mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="ti ti-user-plus me-2"></i>Add New Client</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('add.client') }}" method="post" enctype="multipart/form-data" id="add_client_form">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <label for="add_client_name" class="form-label">Client Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter client name" id="add_client_name" value="{{ old('name') }}" pattern="[A-Za-z\s]+" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                        @error('name')<span class="text-danger" style="font-size:.8rem;">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <label for="add_client_mobile" class="form-label">Mobile Number</label>
                        <input type="number" name="mobile" class="form-control" id="add_client_mobile" placeholder="Enter 10-digit number" maxlength="10" value="{{ old('mobile') }}">
                        @error('mobile')<span class="text-danger" style="font-size:.8rem;">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <button type="submit" class="btn btn-premium w-100" id="add_save_client">
                            <i class="ti ti-device-floppy me-1"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Client List --}}
    <div class="card card-premium animate-fade-in-delay-1">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0"><i class="ti ti-list me-2"></i>Client List</h5>
            <div class="d-flex align-items-center gap-2">
                <i class="ti ti-search text-muted"></i>
                <input type="search" id="clientSearch" class="form-control form-control-sm" placeholder="Search clients..." style="width:220px;">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">#</th>
                            <th>Client Name</th>
                            <th>Mobile Number</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $serialNumber = ($clients->currentPage() - 1) * $clients->perPage() + 1; @endphp
                        @forelse ($clients as $client)
                        <tr>
                            <td class="fw-semibold">{{ $serialNumber++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar-initial rounded-circle bg-label-primary fw-semibold me-3" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </span>
                                    <span class="fw-semibold">{{ $client->name }}</span>
                                </div>
                            </td>
                            <td>{{ $client->mobile }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-premium-sm btn-outline-premium edit_client_btn" title="Edit"
                                        data-id="{{ $client->id }}"
                                        data-name="{{ $client->name }}"
                                        data-mobile="{{ $client->mobile }}"
                                        data-bs-toggle="modal" data-bs-target="#edit_client">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button class="btn btn-premium-sm btn-outline-danger delete_client_btn" title="Delete"
                                        data-id="{{ $client->id }}"
                                        data-bs-toggle="modal" data-bs-target="#delete_client">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="text-center py-5">
                                    <i class="ti ti-users-off" style="font-size:3rem;color:#dee2e6;"></i>
                                    <p class="text-muted mt-2 mb-0">No clients found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="no-records-found text-center py-4" style="display:none;">
                <i class="ti ti-search-off" style="font-size:2rem;color:#dee2e6;"></i>
                <p class="text-muted mt-1 mb-0">No records found matching your search.</p>
            </div>
            @if ($clients->count() > 0)
            <div class="px-4 py-3 border-top d-flex justify-content-end">
                {{ $clients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Edit Client Modal --}}
<div class="modal fade" id="edit_client" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="ti ti-edit me-2"></i>Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editclientform" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" class="client_id" name="id" value="" />
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="name" id="edit_client_name" class="form-control client_name" placeholder="Enter client name"
                            pattern="[A-Za-z\s]+" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                        <span id="edit_name_error" class="text-danger edit_name_error" style="font-size:.8rem;"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="number" name="mobile" id="edit_client_mobile" class="form-control client_mobile" placeholder="Enter 10-digit number">
                        <span id="edit_mobile_error" class="text-danger edit_mobile_error" style="font-size:.8rem;"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium" id="edit_save_client">
                        <i class="ti ti-device-floppy me-1"></i> Update Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="delete_client" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <i class="ti ti-alert-triangle" style="font-size:3rem;color:var(--pre-warning);"></i>
                <h5 class="fw-bold mt-3 mb-2">Confirm Deletion</h5>
                <p class="text-muted mb-0">Are you sure you want to delete this client? This action cannot be undone.</p>
            </div>
            <form id="deleteClientForm">
                @csrf
                @method('GET')
                <input type="hidden" id="client_del_id" name="client_id" value="">
            </form>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-gradient-danger" id="confirm_delete">
                    <i class="ti ti-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extrascript')
<style>
    .pagination-container nav .justify-between { display: none; }
    .no-records-found { text-align: center; color: #dc3545; margin-top: 20px; font-size: 18px; display: none; }
    .pagination svg { width: 24px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {
        function attachEventHandlers() {
            $('.edit_client_btn').click(function() {
                $('.client_id').val($(this).data('id'));
                $('.client_name').val($(this).data('name'));
                $('.client_mobile').val($(this).data('mobile'));
                $('.edit_name_error, .edit_mobile_error').empty();
            });

            $('.delete_client_btn').click(function() {
                $('#client_del_id').val($(this).data('id'));
            });

            $('#confirm_delete').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: '/erp/delete-client/' + $('#client_del_id').val(),
                    data: $('#deleteClientForm').serialize(),
                    success: function(response) {
                        if (response.success == true) {
                            location.reload();
                        } else {
                            alert('Failed to delete the client.');
                        }
                    }
                });
            });
        }

        attachEventHandlers();

        // Search
        $('#clientSearch').on('keyup', function() {
            $.ajax({
                url: '/erp/client',
                type: 'GET',
                data: { search: $(this).val().toLowerCase() },
                success: function(response) {
                    var tbody = $('tbody');
                    tbody.empty();
                    var serialNumber = 1;

                    if (response.clients.length === 0) {
                        $('.no-records-found').show();
                        $('.pagination-container').hide();
                    } else {
                        $('.no-records-found').hide();
                        $('.pagination-container').show().html(response.pagination);
                    }

                    $.each(response.clients, function(_, client) {
                        var initial = client.name ? client.name.charAt(0).toUpperCase() : '?';
                        tbody.append(`
                            <tr>
                                <td class="fw-semibold">${serialNumber++}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar-initial rounded-circle bg-label-primary fw-semibold me-3" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">${initial}</span>
                                        <span class="fw-semibold">${client.name}</span>
                                    </div>
                                </td>
                                <td>${client.mobile}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-premium-sm btn-outline-premium edit_client_btn" title="Edit" data-id="${client.id}" data-name="${client.name}" data-mobile="${client.mobile}" data-bs-toggle="modal" data-bs-target="#edit_client"><i class="ti ti-pencil"></i></button>
                                        <button class="btn btn-premium-sm btn-outline-danger delete_client_btn" title="Delete" data-id="${client.id}" data-bs-toggle="modal" data-bs-target="#delete_client"><i class="ti ti-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                    attachEventHandlers();
                }
            });
        });

        // Edit form submit
        $('#editclientform').on('submit', function(event) {
            event.preventDefault();
            var id = $('.client_id').val();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/erp/edit-client/' + id,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success == false) {
                        $('.edit_name_error, .edit_mobile_error').empty();
                        for (var field in response.errors) {
                            response.errors[field].forEach(function(msg) {
                                $('#edit_client_' + field).after('<span class="text-danger edit_' + field + '_error" style="font-size:.8rem;">' + msg + '</span>');
                            });
                        }
                    } else {
                        $('#edit_client').modal('hide');
                        location.reload();
                    }
                }
            });
        });
    });
});
</script>
@endsection


