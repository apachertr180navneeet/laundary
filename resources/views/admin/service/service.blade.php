@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container{
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg{
        width: 30px;
    }

    .pagination-container nav .justify-between{
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none; /* Hidden by default */
    }

</style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="add_client_form_area_hp mb-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Success message -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Error message -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <h4>Add Services</h4>
                        <!-- Form to add services -->
                        <form action="{{ route('add.services') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center justify-content-center">
                                <!-- Services Name Input -->
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_services_name" class="form-label">Services Name</label>
                                        <input type="text" name="servicesname" class="form-control" placeholder="Enter services Name" id="add_services_name" value="{{ old('servicesname') }}">
                                    </div>
                                    <span class="alert text-danger" id="add_services_name_error">
                                        @error('servicesname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <!-- Services Short Name Input -->
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_services_short_name" class="form-label">Services Short Name</label>
                                        <input type="text" name="servicesshortname" class="form-control" id="add_services_short_name" placeholder="Enter services Short Name" value="{{ old('servicesshortname') }}">
                                    </div>
                                    <span class="alert text-danger" id="add_services_short_name_error">
                                        @error('servicesshortname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <!-- Submit Button -->
                                <div class="col">
                                    <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label"></label>
                                        <button type="submit" class="btn btn_1F446E_hp w-100" id="add_save_client">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Edit client Modal--->
            <div class="modal fade" id="edit_services" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit services</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editservicesform" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" class="edit_services_id" name="id" value="" />
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="edit_services_name" class="form-label fw-blod">Services Name</label>
                                    <input type="text" name="servicesname" class="form-control" placeholder="Enter services Name" id="edit_services_name">
                                    <span id="edit_services_name_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="edit_services_short_name" class="form-label fw-blod">Services Short Name</label>
                                    <input type="text" name="servicesshortname" class="form-control" id="edit_services_short_name" placeholder="Enter services Short Name">
                                    <span id="edit_services_short_name_error" class="alert text-danger"></span>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_1F446E_hp" id="edit_save_cateogry">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <h4>Services List</h4>
                            {{-- <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="servicesSearch" class="form-control"
                                    placeholder="Searching ...">
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="table_head_1f446E">
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Services Name</th>
                                                <th>Services Short Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $serialNumber = 1;
                                            @endphp
                                            @foreach ($services as $service)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{ $service->name }}</td>
                                                    <td>{{ $service->short_name }}</td>
                                                    <td>
                                                        <div class="Client_table_action_area">
                                                            <button class="btn Client_table_action_icon px-2 edit_services_btn" data-id="{{ $service->id }}">
                                                                <i class="tf-icons ti ti-pencil"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="no-records-found">No records found related to your search.</div>
                                        @if ($services->count() > 0)
                                            <div class="pagination-container">
                                                {{ $services->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Wait for the DOM content to be fully loaded before executing any script
        document.addEventListener("DOMContentLoaded", function () {

            // Utility function to handle AJAX errors
            function handleAjaxError(xhr) {
                console.error("An error occurred:", xhr.responseText);
            }

            // Utility function to handle AJAX success responses
            function handleAjaxSuccess(response, successCallback) {
                if (response.success) {
                    // If the response is successful, execute the success callback
                    successCallback(response);
                } else if (response.errors) {
                    // Handle and display validation errors if they exist
                    $('#edit_services_name_error').text(response.errors.servicesname || '');
                    $('#edit_services_short_name_error').text(response.errors.servicesshortname || '');
                    $('#edit_services_image_error').text(response.errors.servicesimage || '');
                }
            }

            // Function to attach event handlers for delete and edit actions
            function attachEventHandlers() {
                // Handle delete button click event
                $(document).on('click', '.delete_client_btn', function () {
                    const id = $(this).data("id");
                    $("#client_del_id").val(id); // Set the ID in the delete confirmation modal
                    $("#delete_client").modal("show"); // Show the delete confirmation modal
                });

                // Handle confirmation of delete action
                $(document).on('click', '#confirm_delete', function (e) {
                    e.preventDefault();
                    const id = $("#client_del_id").val();

                    // Send a request to delete the service
                    $.ajax({
                        type: "GET",
                        url: `/admin/delete-services/${id}`,
                        data: $("#deleteClientForm").serialize(),
                        success: function () {
                            // Hide the modal and reload the page on success
                            $("#delete_client").modal("hide");
                            window.location.reload();
                        },
                        error: handleAjaxError
                    });
                });

                // Handle edit button click event
                $(document).on('click', '.edit_services_btn', function () {
                    const servicesId = $(this).data("id");

                    // Fetch service data to populate the edit form
                    $.ajax({
                        url: `/admin/services/${servicesId}/edit`,
                        type: "GET",
                        success: function (response) {
                            // Populate form fields with service data
                            $("#edit_services_name").val(response.name);
                            $("#edit_services_short_name").val(response.short_name);
                            $(".edit_services_id").val(response.id);

                            // Set the service image if available
                            const baseUrl = "{{ asset('storage/services/') }}";
                            const imageUrl = response.image ? `${baseUrl}/${response.image}` : "";
                            $("#catimg").attr("src", imageUrl);

                            // Show the edit modal
                            $("#edit_services").modal("show");
                        },
                        error: handleAjaxError
                    });
                });

                // Handle form submission for editing a service
                $('#editservicesform').on('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this); // Get form data
                    const servicesId = $('.edit_services_id').val();

                    // Send a POST request to update the service
                    $.ajax({
                        url: `/admin/edit-services/${servicesId}`,
                        method: 'POST',
                        data: formData,
                        processData: false, // Prevent jQuery from processing data
                        contentType: false, // Ensure correct form submission with files
                        success: function (response) {
                            // On success, execute success callback
                            handleAjaxSuccess(response, function () {
                                $('#edit_services').modal('hide'); // Close modal
                                servicesSearch(); // Refresh the service list
                                alert('Services updated successfully');
                                window.location.reload(true); // Reload page for latest changes
                            });
                        },
                        error: function (xhr) {
                            handleAjaxError(xhr);
                            alert('An error occurred while updating the services');
                        }
                    });
                });
            }

            // Function to perform service search and update the list dynamically
            function servicesSearch() {
                const searchQuery = $('#servicesSearch').val();

                // Send a GET request to search services based on input
                $.ajax({
                    url: "/admin/services",
                    type: "GET",
                    data: { search: searchQuery },
                    success: function (response) {
                        const services = response.services;
                        const pagination = response.pagination || ''; // Pagination HTML

                        // Show or hide "no records found" message and pagination controls
                        if (services.length === 0) {
                            $(".no-records-found").show();
                            $(".pagination-container").hide();
                        } else {
                            $(".no-records-found").hide();
                            $(".pagination-container").show().html(pagination);
                        }

                        // Clear previous table results
                        const tbody = $("#servicesTable tbody");
                        tbody.empty();

                        // Append new rows to the service table
                        $.each(services, function (index, service) {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${service.name}</td>
                                    <td>${service.short_name}</td>
                                    <td>
                                        <img src="/storage/services/${service.image}" alt="${service.name}" width="50" height="50">
                                    </td>
                                    <td>
                                        <div class="Client_table_action_area">
                                            <button class="btn Client_table_action_icon px-2 edit_services_btn"
                                                data-id="${service.id}" data-bs-toggle="modal" data-bs-target="#edit_services">
                                                <i class="tf-icons ti ti-pencil"></i>
                                            </button>
                                            <button class="btn Client_table_action_icon px-2 delete_client_btn"
                                                data-id="${service.id}" data-bs-toggle="modal" data-bs-target="#delete_client">
                                                <i class="tf-icons ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        // Reattach event handlers after updating the DOM
                        attachEventHandlers();
                    },
                    error: handleAjaxError
                });
            }

            // Initial setup
            attachEventHandlers(); // Attach event handlers when the DOM is loaded

            // Search input event listener
            $("#servicesSearch").on("keyup", function () {
                servicesSearch(); // Trigger search on keyup
            });

            // Utility function to validate input fields (allow only alphabets and spaces)
            function validateInput(input) {
                const regex = /^[A-Za-z\s]+$/; // Regular expression for validation
                return regex.test(input); // Test input against the regex
            }

            // Add form validation for input fields
            function addValidationListeners() {
                document.getElementById('add_services_name').addEventListener('input', function (event) {
                    const value = event.target.value;
                    const errorMsg = !validateInput(value) ? 'Only alphabets and spaces are allowed' : '';
                    document.getElementById('add_services_name_error').innerText = errorMsg;
                });

                document.getElementById('add_services_short_name').addEventListener('input', function (event) {
                    const value = event.target.value;
                    const errorMsg = !validateInput(value) ? 'Only alphabets and spaces are allowed' : '';
                    document.getElementById('add_services_short_name_error').innerText = errorMsg;
                });

                // Add validation for edit form fields
                document.getElementById('edit_services_name').addEventListener('input', function (event) {
                    const value = event.target.value;
                    const errorMsg = !validateInput(value) ? 'Only alphabets and spaces are allowed' : '';
                    document.getElementById('edit_services_name_error').innerText = errorMsg;
                });

                document.getElementById('edit_services_short_name').addEventListener('input', function (event) {
                    const value = event.target.value;
                    const errorMsg = !validateInput(value) ? 'Only alphabets and spaces are allowed' : '';
                    document.getElementById('edit_services_short_name_error').innerText = errorMsg;
                });
            }

            // Add validation listeners for input fields
            addValidationListeners();

        });
    </script>
@endsection
