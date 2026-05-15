@extends('backend.layouts.app')
@section('content')
<style>
    /* Your existing styles */
    .pagination-container {
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg {
        width: 30px;
    }
    .pagination-container nav .justify-between {
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none;
    }
    .add-button {
        margin-right: 15px;
    }
     /* Loader Overlay */
     .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Optional: Button Spinner Alignment */
    .btn .spinner-border {
        margin-right: 5px;
    }
</style>
<div id="globalLoader" class="loader-overlay" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp">
            <div class="card">
                <div class="card-header">
                    <h4>Add Item</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    <form id="item-form" action="{{ route('store.item') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ old('item_name') }}">
                                    <span class="text-danger" id="item_name_error">
                                        @error('item_name') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" id="addcategory-container">
                                @php
                                    $itemdetails = old('itemdetail', []);
                                    if (empty($itemdetails)) {
                                        $itemdetails = [1 => ['category' => '', 'service' => [null], 'price' => [null]]];
                                    }
                                @endphp

                                @foreach ($itemdetails as $index => $detail)
                                    <div class="row addcategory" id="addcategory{{ $index }}">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="category{{ $index }}" class="form-label">Category</label>
                                                <select class="form-control category-select" name="itemdetail[{{ $index }}][category]" id="category{{ $index }}">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categorys as $category)
                                                        <option value="{{ $category->name }}" {{ old("itemdetail.$index.category") == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="category{{ $index }}_error">
                                                    @error("itemdetail.$index.category") {{ $message }} @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @if ($loop->first)
                                                <button type="button" id="add-more" class="btn btn-primary">+ Category</button>
                                            @else
                                                <button type="button" class="btn btn-danger remove-category" data-id="{{ $index }}">- Category</button>
                                            @endif
                                        </div>

                                        <div class="col-md-12 mt-4" id="addservice-container{{ $index }}">
                                            @foreach ($detail['service'] as $sindex => $service)
                                                <div class="row addservice" id="addservice{{ $index }}_{{ $sindex + 1 }}">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="service{{ $index }}_{{ $sindex + 1 }}" class="form-label">Service</label>
                                                            <select class="form-control service-select" name="itemdetail[{{ $index }}][service][]" id="service{{ $index }}_{{ $sindex + 1 }}">
                                                                <option value="">Select Service</option>
                                                                @foreach ($services as $serviceOption)
                                                                    <option value="{{ $serviceOption->name }}" {{ old("itemdetail.$index.service.$sindex") == $serviceOption->name ? 'selected' : '' }}>{{ $serviceOption->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger" id="service{{ $index }}_{{ $sindex + 1 }}_error">
                                                                @error("itemdetail.$index.service.$sindex") {{ $message }} @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="price{{ $index }}_{{ $sindex + 1 }}" class="form-label">Price</label>
                                                        <input type="text" class="form-control price-input" name="itemdetail[{{ $index }}][price][]" placeholder="Enter price" id="price{{ $index }}_{{ $sindex + 1 }}" value="{{ old("itemdetail.$index.price.$sindex") }}" maxlength="4">
                                                        <span class="text-danger" id="price{{ $index }}_{{ $sindex + 1 }}_error">
                                                            @error("itemdetail.$index.price.$sindex") {{ $message }} @enderror
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if ($sindex == 0 && $index == 1)
                                                            <button type="button" class="btn btn-primary add-more-service" data-category-id="{{ $index }}">+ Service</button>
                                                        @else
                                                            <button type="button" class="btn btn-danger remove-service" data-id="{{ $index }}_{{ $sindex + 1 }}">- Service</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" form="item-form" id="clickpardisable" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    $(function () {
        var categoryIndex = {{ count($itemdetails) }};
        var serviceIndices = @json(array_map(function($detail) { return count($detail['service']); }, $itemdetails));

        // Disable copy/paste on price inputs
        $(document).on('paste copy', '.price-input', e => e.preventDefault());

        // Limit price input to 4 digits
        $(document).on('input', '.price-input', function () {
            var value = $(this).val().replace(/[^0-9]/g, '').substring(0, 4);
            $(this).val(value === '0' ? '' : value);
        });

        // Add new category
        $('#add-more').click(function () {
            categoryIndex++;
            serviceIndices[categoryIndex] = 1;
            $('#addcategory-container').append(createCategoryRow(categoryIndex));
            disableSelectedCategories();  // Disable already selected categories
        });

        // Add new service
        $(document).on('click', '.add-more-service', function () {
            var categoryId = $(this).data('category-id');
            serviceIndices[categoryId]++;
            $('#addservice-container' + categoryId).append(createServiceRow(categoryId, serviceIndices[categoryId]));
            disableSelectedServices(categoryId); // Disable already selected services
        });

        // Remove category
        $(document).on('click', '.remove-category', function () {
            var categoryId = $(this).data('id');
            $('#addcategory' + categoryId).remove();
            disableSelectedCategories(); // Re-enable categories after removing
        });

        // Remove service
        $(document).on('click', '.remove-service', function () {
            var id = $(this).data('id');
            $('#addservice' + id).remove();
            var categoryId = id.split('_')[0]; // Extract categoryId from the service ID
            disableSelectedServices(categoryId); // Re-enable services after removing
        });

        // Handle category selection change
        $(document).on('change', '.category-select', function () {
            disableSelectedCategories();  // Disable already selected categories
        });

        // Handle service selection change
        $(document).on('change', '.service-select', function () {
            var categoryId = $(this).closest('.addcategory').attr('id').replace('addcategory', '');
            disableSelectedServices(categoryId);  // Disable already selected services
        });

        // Function to disable already selected categories
        function disableSelectedCategories() {
            var selectedCategories = [];

            // Get all selected categories
            $('.category-select').each(function () {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedCategories.push(selectedValue);
                }
            });

            // Disable selected categories in other dropdowns
            $('.category-select').each(function () {
                var currentSelect = $(this);
                currentSelect.find('option').each(function () {
                    var optionValue = $(this).val();
                    if (selectedCategories.includes(optionValue) && optionValue !== currentSelect.val()) {
                        $(this).attr('disabled', true);  // Disable already selected categories
                    } else {
                        $(this).attr('disabled', false);  // Re-enable unselected categories
                    }
                });
            });
        }

        // Function to disable already selected services in a specific category
        function disableSelectedServices(categoryId) {
            var selectedServices = [];

            // Get all selected services within the category
            $('#addcategory' + categoryId + ' .service-select').each(function () {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedServices.push(selectedValue);
                }
            });

            // Disable selected services in other dropdowns within the category
            $('#addcategory' + categoryId + ' .service-select').each(function () {
                var currentSelect = $(this);
                currentSelect.find('option').each(function () {
                    var optionValue = $(this).val();
                    if (selectedServices.includes(optionValue) && optionValue !== currentSelect.val()) {
                        $(this).attr('disabled', true); // Disable already selected services
                    } else {
                        $(this).attr('disabled', false); // Re-enable other options
                    }
                });
            });
        }

        // Validate and submit form
        $('#item-form').submit(function (e) {
            var isValid = true;

            $('#item_name').each(function () {
                if (!$(this).val()) {
                    $('#' + $(this).attr('id') + '_error').text('This field is required.');
                    isValid = false;
                } else {
                    $('#' + $(this).attr('id') + '_error').text('');
                }
            });

            // Validate categories and services
            $('.category-select, .service-select').each(function () {
                if (!$(this).val()) {
                    $('#' + $(this).attr('id') + '_error').text('This field is required.');
                    isValid = false;
                } else {
                    $('#' + $(this).attr('id') + '_error').text('');
                }
            });

            // Validate prices
            $('.price-input').each(function () {
                if (!$(this).val() || $(this).val() <= 0) {
                    $('#' + $(this).attr('id') + '_error').text('Please enter a valid price.');
                    isValid = false;
                } else {
                    $('#' + $(this).attr('id') + '_error').text('');
                }
            });

            if (!isValid) e.preventDefault();
        });

        // Helper function to create category row
        function createCategoryRow(index) {
            return `
                <div class="row addcategory" id="addcategory${index}">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category${index}" class="form-label">Category</label>
                            <select class="form-control category-select" name="itemdetail[${index}][category]" id="category${index}">
                                <option value="">Select Category</option>
                                @foreach ($categorys as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="category${index}_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger remove-category" data-id="${index}">- Category</button>
                    </div>
                    <div class="col-md-12 mt-4" id="addservice-container${index}">
                        <div class="row addservice" id="addservice${index}_1">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="service${index}_1" class="form-label">Service</label>
                                    <select class="form-control service-select" name="itemdetail[${index}][service][]" id="service${index}_1">
                                        <option value="">Select Service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->name }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="service${index}_1_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="price${index}_1" class="form-label">Price</label>
                                <input type="text" class="form-control price-input" name="itemdetail[${index}][price][]" placeholder="Enter price" id="price${index}_1" maxlength="4">
                                <span class="text-danger" id="price${index}_1_error"></span>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary add-more-service" data-category-id="${index}">+ Service</button>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        // Helper function to create service row
        function createServiceRow(categoryId, serviceId) {
            return `
                <div class="row addservice" id="addservice${categoryId}_${serviceId}">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="service${categoryId}_${serviceId}" class="form-label">Service</label>
                            <select class="form-control service-select" name="itemdetail[${categoryId}][service][]" id="service${categoryId}_${serviceId}">
                                <option value="">Select Service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->name }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="service${categoryId}_${serviceId}_error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="price${categoryId}_${serviceId}" class="form-label">Price</label>
                        <input type="text" class="form-control price-input" name="itemdetail[${categoryId}][price][]" id="price${categoryId}_${serviceId}" maxlength="4">
                        <span class="text-danger" id="price${categoryId}_${serviceId}_error"></span>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger remove-service" data-id="${categoryId}_${serviceId}">- Service</button>
                    </div>
                </div>`;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('item-form');
        const submitButton = document.getElementById('clickpardisable');
        const globalLoader = document.getElementById('globalLoader');

        form.addEventListener('submit', function(event) {
            // Show the loader
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            //globalLoader.style.display = 'flex';
            // The form will submit naturally without preventing the default behavior
        });

        // Handle form reloading (e.g., after validation errors)
        // window.addEventListener('load', function() {
        //     @if ($errors->any())
        //         // Re-enable the submit button and hide the loader
        //         submitButton.disabled = false;
        //         submitButton.innerHTML = 'Save';
        //         globalLoader.style.display = 'none';
        //     @endif

        //     @if(session('success'))
        //         // Re-enable the submit button and hide the loader
        //         submitButton.disabled = false;
        //         submitButton.innerHTML = 'Save';
        //         globalLoader.style.display = 'none';
        //     @endif
        // });
    });
</script>

@endsection
