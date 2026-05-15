@extends('backend.layouts.app')
@section('content')
    <style>
        .disabled {
            pointer-events: none;
        }

        .btn-danger {
            display: none;
            /* Ensure it's hidden by default */
        }

        .dev-hide {
            display: none !important;
        }

        .service-section.bg-primary {
            color: white;
        }

        .pop-service-section {
            margin-right: 2%;
        }

        #availableItemsList .border {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp Add_order_page_section">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="client_list_heading_area">
                                    <h4>
                                        Add Order
                                    </h4>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <form action="{{ route('add.order') }}" method="POST" id="addOrderFormValidation"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-2">
                                    <!-- Form Inputs for Client and Order Details -->
                                    <div class="row">
                                        <!-- Client Number -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_num" class="form-label">Client Number</label>
                                                <input type="text" value="{{ old('mobile', $order->mobile ?? '') }}"
                                                    id="number" name="client_num" class="form-control"
                                                    placeholder="Client Number" />
                                            </div>
                                        </div>
                                        <!-- Client Name -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_name" class="form-label">Client Name</label>
                                                <input type="text" id="client_name"
                                                    value="{{ old('name', $order->name ?? '') }}" name="client_name"
                                                    class="form-control" placeholder="Client Name" />
                                            </div>
                                        </div>
                                        <!-- Booking Date -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <input type="hidden" id="booking_date" value="{{ $currentdate }}"
                                                    name="booking_date" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- Booking Time -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <input type="hidden" id="booking_time" value="{{ $currenttime }}"
                                                    name="booking_time" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-xl-12">

                                        </div>
                                        <!-- Gross Total Section -->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3">
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="gross_total" id="gross_total" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Gross Total:</h6>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6 id="grossTotal">0.0</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Discount Amount:</h6>
                                                </div>
                                                <div id="discountAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0.0</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="total_qty" id="total_qty" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Count:</h6>
                                                </div>
                                                <div id="totalQty" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0 pc</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Amount:</h6>
                                                </div>
                                                <div id="totalAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0</h6>
                                                </div>
                                            </div>
                                            <hr class="px-2">
                                        </div>
                                        <!-- Delivery Date -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                                <input type="date" id="delivery_date"
                                                    value="{{ old('delivery_date', $order->delivery_date ?? '') }}"
                                                    name="delivery_date" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- Delivery Time -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_time" class="form-label">Delivery Time</label>
                                                <select id="delivery_time" name="delivery_time" class="form-control valid">
                                                    @foreach ($timeSlots['time_ranges'] as $time)
                                                        <option value="{{ $time['start'] }}"
                                                            {{ old('delivery_time', $order->delivery_time ?? '') == $time['start'] ? 'selected' : '' }}>
                                                            {{ $time['range'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Discount Offer -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="discount" class="form-label">Discount Offer</label>
                                                <select name="discount" id="discount" class="form-select">
                                                    <option value="0" selected>Select Discount Offer</option>
                                                    @foreach ($discounts as $discount)
                                                        <option value="{{ $discount->amount }}">{{ $discount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Add_order_btn_area text-end">
                                                <button class="btn w-100" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#CreateOrder">Save</button>
                                            </div>
                                        </div>
                                        <!-- Create Order Model -->
                                        <div class="modal fade" id="CreateOrder" tabindex="-1"
                                            aria-labelledby="CreateOrderLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5>Would you like to Create a New Order?</h5>
                                                        <button type="submit" class="btn btn-primary" id="yesButton"
                                                            data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end -->
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-2">
                                    <!-- Product Items Section -->
                                    <div class="client_list_area_hp">
                                        <div class="client_list_heading_area w-100">
                                            <div class="client_list_heading_search_area w-100">
                                                <i class="menu-icon tf-icons ti ti-search"></i>
                                                <input type="search" class="form-control" placeholder="Searching ..."
                                                    id="searchItem" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="searchData">
                                        <div id="productItemError" class="alert alert-danger" style="display: none;">
                                            Item Not Found.
                                        </div>
                                        <!-- Loop through the products and their grouped details -->
                                        @foreach ($groupedProductItems as $groupedProductItem)
                                            @php
                                                $productItem = $groupedProductItem['product_item'];
                                                $groupedDetails = $groupedProductItem['grouped_details'];
                                            @endphp
                                            <div class="border rounded p-2 mb-2">
                                                <div class="row">
                                                    <div class="col-lg-9 col-md-9 mainopdiv">
                                                        <h6 class="mb-2 text-dark searchProductName"
                                                            data-name="{{ $productItem->name }}">{{ $productItem->name }}
                                                        </h6>

                                                        <div id="categories-{{ $productItem->id }}"
                                                            class="category-section mb-3">
                                                            <!-- Display all categories for this product -->
                                                            @foreach ($groupedDetails as $category => $services)
                                                                <span
                                                                    onclick="selectCategory(this, '{{ $category }}', '{{ $productItem->id }}')"
                                                                    class="badge mb-2 subcategory bg-secondary"
                                                                    id="category-{{ $productItem->id }}-{{ $category }}">{{ $category }}
                                                                </span>
                                                            @endforeach
                                                        </div>

                                                        <div id="service-group-{{ $productItem->id }}"
                                                            class="service-group">
                                                            <!-- Show services for the first category by default -->
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 text-center">
                                                        <img class="mb-2"
                                                            src="{{ url('images/categories_img/' . $productItem->image) }}"
                                                            alt="{{ $productItem->name }}" style="width: 50px;">
                                                        <div class="Add_order_btn_area">
                                                            <button type="button" id="addbtnpreview"
                                                                class="btn add-product-btn" data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvasRight"
                                                                aria-controls="offcanvasRight"
                                                                data-product-name="{{ $productItem->name }}"
                                                                data-images="{{ url('images/categories_img/' . $productItem->image) }}"
                                                                data-product-id="{{ $productItem->id }}">Add</button>
                                                            <button
                                                                class="btn btn-danger dev-hide waves-effect waves-light"
                                                                id="productId{{ $productItem->id }}" type="button"
                                                                onclick="removeProductItem('{{ $productItem->id }}')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Offcanvas Right Panel -->
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                        aria-labelledby="offcanvasRightLabel">
                                        <div class="offcanvas-header border-bottom">
                                            <h5 id="offcanvasRightLabel">Item Details</h5>
                                            <button id="addOrderModel" type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body mainopdiv">
                                            <!-- Product Name -->
                                            <div class="border-bottom mb-4">
                                                <h6 class="mb-2 text-dark" id="popupProductName">Select Product Name</h6>
                                            </div>

                                            <!-- Categories -->
                                            <div class="border-bottom mb-4">
                                                <h6 class="mb-2 text-dark">Select Categories</h6>
                                                <div id="popupCategories">
                                                    <!-- Categories will be dynamically populated here -->
                                                </div>
                                            </div>

                                            <!-- Services -->
                                            <div class="border-bottom mb-4 selectServices" id="selectServices">
                                                <h6 class="mb-2 text-dark">Select Services</h6>
                                                <div id="popupServices">
                                                    <!-- Services will be dynamically populated based on selected category -->
                                                </div>
                                            </div>

                                            <!-- Garment Details (optional section based on requirements) -->
                                            <div class="border-bottom mb-4" id="garmentDetailsContainer"
                                                style="display: none;">
                                                <h6 class="mb-2 text-dark">Garment Details</h6>
                                                <div id="garmentDetails">
                                                    <!-- Garment details will be dynamically populated here -->
                                                </div>
                                                <input type="hidden" id="totalQtyPlsMns" name="totalQtyPlsMns"
                                                    value="0">
                                                <button type="button" class="btn btn-success mt-2"
                                                    id="addGarmentBtn">Add Garment</button>

                                                <h5 id="offcanvasRightLabel">Selected Garments</h5>
                                                <div class="offcanvas-body" id="selectedGarmentsList">
                                                    <!-- Selected items will be dynamically populated here -->
                                                </div>
                                            </div>

                                            <!-- Quantity Input -->
                                            <div class="border-bottom mb-4">
                                                <div class="input-group">
                                                    <label for="qtyPlsMns" class="form-label">Count</label>
                                                    <input type="hidden" class="form-control" value=""
                                                        id="productName" name="productName" placeholder="" />
                                                    <input type="hidden" class="form-control" value=""
                                                        id="productCategory" name="productCategory" placeholder="" />
                                                    <input type="hidden" class="form-control" value=""
                                                        id="productservice" name="productservice" placeholder="" />
                                                    <input type="hidden" class="form-control" value=""
                                                        id="productprice" name="productprice" placeholder="" />
                                                    <div class="input-group mb-3">
                                                        <button type="button" class="input-group-text decrease"><i
                                                                class="fa-solid fa-minus"></i></button>
                                                        <input type="text" class="form-control text-center piece-count"
                                                            value="0" id="qtyPlsMns" name="qty" placeholder="0"
                                                            readonly />
                                                        <button type="button" class="input-group-text increase"><i
                                                                class="fa-solid fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="lundarycategory">
                                                    <label for="exampleInputEmail1">Weight in (KG)</label>
                                                    <input type="text" class="form-control" id="lundaryweight"
                                                        name="lundaryweight" placeholder="Enter Lundary by Weight">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="offcanvas-footer px-4 pb-2">
                                            <button type="button" id="addRightOdrbtn"
                                                class="btn w-100 btn-primary">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Create Order Modal -->
                                <div class="modal fade" id="CreateOrder" tabindex="-1"
                                    aria-labelledby="CreateOrderLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <h5>Would you like to create a new order?</h5>
                                                <button type="submit" class="btn btn-primary" id="yesButton"
                                                    data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal for showing items except "Laundry By Weight" -->
                                <div class="modal fade" id="garmentItemsModal" tabindex="-1"
                                    aria-labelledby="garmentItemsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="garmentItemsModalLabel">Available Items</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" id="availableItemsList">
                                                    <!-- Items will be dynamically populated here -->
                                                </div>
                                                <button type="button" class="btn btn-primary"
                                                    id="addLaundaryItem">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var allProductDetail = [];
            var groupedProductItems = @json($groupedProductItems); // Passing PHP data to JavaScript

            let totalQty = 0; // To track total quantity
            let grossTotal = 0; // To track total gross amount
            let totalAmount = 0; // To track the total amount

            // When the discount changes, recalculate the total
            document.getElementById('discount').addEventListener('change', function() {
                updateTotals(); // Recalculate totals including the discount
            })

            // Function to add an item and update totals
            function addItemToTotals(itemName, itemQty, itemPrice, itemService, clothLundaryQty, itemCategory) {

                // Ensure itemQty and itemPrice are numbers and not NaN
                itemQty = parseInt(itemQty) || 0;
                itemPrice = parseFloat(itemPrice) || 0;
                clothLundaryQty = parseInt(clothLundaryQty) || 0; // Handle if clothLundaryQty is undefined or null

                let totalPrice;

                if (itemName === "Laundry By Weight") {
                    totalPrice = parseFloat(clothLundaryQty * itemPrice).toFixed(2);
                } else {
                    totalPrice = parseFloat(itemQty * itemPrice).toFixed(2);
                }
                updateTotals();
            }

            function updateTotals() {
                var totalQty = 0;
                var grossTotal = 0;

                document.querySelectorAll('.row.border').forEach(function(itemRow) {
                    var itemQty = parseInt(itemRow.querySelector('input[name="itemqty[]"]').value) || 0;
                    var clothLundaryQty = parseInt(itemRow.querySelector('input[name="clothlundaryqty[]"]').value) ||
                        0; // Safely handle Laundry By Weight
                    var itemPrice = parseFloat(itemRow.querySelector('input[name="itemprice[]"]').value) || 0;

                    // If Laundry By Weight is involved, use clothLundaryQty for the calculation
                    var isLaundryByWeight = clothLundaryQty > 0; // Check if Laundry By Weight item
                    var itemTotal = isLaundryByWeight ? clothLundaryQty * itemPrice : itemQty * itemPrice;

                    // Add to totals
                    totalQty += itemQty; // Count Laundry By Weight qty
                    grossTotal += itemTotal; // Add to gross total
                });

                // Update visible DOM elements
                document.getElementById('totalQty').textContent = totalQty + ' pc';
                document.getElementById('grossTotal').textContent = grossTotal.toFixed(2);

                // Update hidden input fields
                document.getElementById('total_qty').value = totalQty; // Set hidden total_qty field
                document.getElementById('gross_total').value = grossTotal.toFixed(2); // Set hidden gross_total field

                // Ensure discount is a valid number
                let discount = parseFloat(document.getElementById('discount').value) || 0;
                let discountAmount = grossTotal * (discount / 100);
                let finalTotalAmount = grossTotal - discountAmount;

                document.getElementById('discountAmount').textContent = discountAmount.toFixed(2);
                document.getElementById('totalAmount').textContent = finalTotalAmount.toFixed(2);
            }




            function selectCategory(element, category, productId) {
                // Get the services for the selected category
                var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(productId));
                var services = productItem.grouped_details[category];

                // Update the services in the service group div
                var serviceGroup = document.getElementById('service-group-' + productId);
                serviceGroup.innerHTML = ''; // Clear previous content

                var serviceDetailsGrid = document.createElement('div');
                serviceDetailsGrid.classList.add('service-details-grid');

                // Select the categories specifically for the current product
                var categorySection = document.querySelector('#categories-' +
                    productId); // Ensure the category section is specific to this product
                var categoryBadges = categorySection.children; // Get the badges for the current product

                // Loop through the category badges to activate the selected one
                for (var i = 0; i < categoryBadges.length; i++) {
                    var badgeText = categoryBadges[i].textContent.trim().replace(/,/g, '');
                    var cleanCategory = category.replace(/,/g, '').trim();

                    if (badgeText === cleanCategory) {
                        categoryBadges[i].classList.add('bg-primary');
                    } else {
                        categoryBadges[i].classList.remove('bg-primary');
                    }
                }

                // Check if the category is "Laundry By Weight"
                var unit = (category.trim() === "Laundry By Weight") ? 'kg' : 'pc';

                // Loop through the services and populate the details
                for (var service in services) {
                    var serviceSection = document.createElement('div');
                    serviceSection.classList.add('service-section');

                    // Create the service name element
                    var serviceName = document.createElement('div');
                    serviceName.classList.add('service-name');
                    serviceName.textContent = service;

                    // Create the service details element
                    var serviceDetails = document.createElement('div');
                    serviceDetails.classList.add('service-details');

                    services[service].forEach(function(detail) {
                        var priceItem = document.createElement('div');
                        priceItem.classList.add('price-item');

                        var priceValue = document.createElement('div');
                        priceValue.classList.add('price-value');
                        // Use the dynamic unit based on the category
                        priceValue.textContent = '₹ ' + detail.price + '/' + unit;

                        priceItem.appendChild(priceValue);
                        serviceDetails.appendChild(priceItem);
                    });

                    serviceSection.appendChild(serviceName);
                    serviceSection.appendChild(serviceDetails);
                    serviceDetailsGrid.appendChild(serviceSection);
                }

                serviceGroup.appendChild(serviceDetailsGrid);
            }



            // Initialize the first category for each product on page load
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($groupedProductItems as $groupedProductItem)
                    @php
                        $firstCategory = array_key_first($groupedProductItem['grouped_details']->toArray());
                    @endphp
                    selectCategory(
                        document.getElementById(
                            'category-{{ $groupedProductItem['product_item']->id }}-{{ $firstCategory }}'),
                        '{{ $firstCategory }}',
                        '{{ $groupedProductItem['product_item']->id }}'
                    );
                @endforeach
            });


            // Add click event listener for the "Add" button
            document.querySelectorAll('.add-product-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Reset the offcanvas before loading new product details

                    var productName = btn.getAttribute('data-product-name');
                    var productId = btn.getAttribute('data-product-id');

                    // Update the product name in the offcanvas
                    document.getElementById('popupProductName').textContent = productName;
                    $('#productName').val(productName);

                    // Get the product item from the groupedProductItems array
                    var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(
                        productId));
                    var categories = productItem.grouped_details;

                    // Populate categories in the offcanvas
                    var categoryContainer = document.getElementById('popupCategories');
                    categoryContainer.innerHTML = ''; // Clear existing categories

                    for (var category in categories) {
                        var categoryBadge = document.createElement('span');
                        categoryBadge.textContent = category;
                        categoryBadge.classList.add('badge', 'mb-2', 'subcategory', 'bg-secondary',
                            'pop-service-section');
                        categoryBadge.onclick = function() {
                            selectPopupCategory(this, this.textContent, productId);
                        };
                        categoryContainer.appendChild(categoryBadge);
                    }

                    // Automatically select the first category and show services
                    var firstCategory = Object.keys(categories)[0];
                    selectPopupCategory(null, firstCategory, productId);
                });
            });

            function selectPopupCategory(element, category, productId) {
                // Get the services for the selected category
                var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(productId));
                var services = productItem.grouped_details[category];

                // Find the select services section
                var serviceGroup = document.getElementById('popupServices');
                var garmentSection = document.getElementById('garmentDetailsContainer');
                var lundarycategory = document.getElementById('lundarycategory');

                // Clear previous services
                serviceGroup.innerHTML = '';

                var categoryBadges = document.getElementById('popupCategories').children;
                for (var i = 0; i < categoryBadges.length; i++) {
                    if (categoryBadges[i].textContent === category) {
                        categoryBadges[i].classList.add('bg-primary');
                        $('#productCategory').val(categoryBadges[i].textContent);
                    } else {
                        categoryBadges[i].classList.remove('bg-primary');
                    }
                }

                // Check if category is "Laundry By Weight"
                if (category === "Laundry By Weight") {
                    // Hide selectServices class and show garmentDetailsContainer
                    garmentSection.style.display = 'block'; // Show "Add Garment" button

                    lundarycategory.style.display = 'block'

                    // Add static services for "Laundry By Weight"
                    services['AV'] = [{
                        price: 5
                    }];
                    services['FS'] = [{
                        price: 5
                    }];
                } else {
                    // Show selectServices class and hide garmentDetailsContainer for other categories
                    garmentSection.style.display = 'none'; // Hide "Add Garment" button

                    lundarycategory.style.display = 'none';
                }

                // Populate the services for the selected category
                var serviceDetailsGrid = document.createElement('div');
                serviceDetailsGrid.classList.add('service-details-grid');

                // Set the unit based on category (if Laundry By Weight, set it to kg, otherwise pc)
                var unit = (category === "Laundry By Weight") ? 'kg' : 'pc';

                for (let service in services) {
                    var serviceSection = document.createElement('div');
                    serviceSection.classList.add('service-section');

                    // Set the data-category attribute for the service section
                    serviceSection.setAttribute('data-service', service);

                    // Make the service clickable
                    serviceSection.addEventListener('click', function() {
                        handleServiceClick(service, services[service]);
                    });

                    var serviceName = document.createElement('div');
                    serviceName.classList.add('service-name');
                    serviceName.textContent = service;

                    var serviceDetails = document.createElement('div');
                    serviceDetails.classList.add('service-details');

                    services[service].forEach(function(detail) {
                        var priceItem = document.createElement('div');
                        priceItem.classList.add('price-item');

                        var priceValue = document.createElement('div');
                        priceValue.classList.add('price-value');
                        // Use the dynamic unit based on the category
                        priceValue.textContent = '₹ ' + detail.price + '/' + unit;

                        priceItem.appendChild(priceValue);
                        serviceDetails.appendChild(priceItem);
                    });

                    serviceSection.appendChild(serviceName);
                    serviceSection.appendChild(serviceDetails);
                    serviceDetailsGrid.appendChild(serviceSection);
                }

                serviceGroup.appendChild(serviceDetailsGrid);
            }



            // This is the function that will handle the click event

            // Maintain state for selected services
            let selectedServices = []; // Maintain the state for selected services

            function handleServiceClick(serviceName, serviceDetails) {
                // Manually add static service details for AV and FS only if not present in serviceDetails
                if (serviceName === 'AV') {
                    serviceDetails = [{
                        service: 'AV',
                        price: 5
                    }];
                } else if (serviceName === 'FS') {
                    serviceDetails = [{
                        service: 'FS',
                        price: 5
                    }];
                }

                // Find the selected service details
                const selectedService = serviceDetails.find(detail => detail.service === serviceName);

                if (!selectedService) {
                    return; // Exit if no matching service is found
                }

                // Helper function to update input fields with selected services and price
                const updateInputs = () => {
                    const selectedNames = selectedServices.map(service => service.service).join(", ");
                    const totalPrice = selectedServices.reduce((sum, service) => sum + parseFloat(service.price), 0);

                    $("#productservice").val(selectedNames); // Set comma-separated service names
                    $("#productprice").val(totalPrice.toFixed(2)); // Set total price (formatted to 2 decimal places)
                };

                // Case 1: If 'DC' is selected, deselect 'SP' and 'ST'
                if (serviceName === 'DC') {
                    // Deselect SP and ST
                    $("#popupServices .service-details-grid .service-section[data-service='SP']").removeClass("bg-primary");
                    $("#popupServices .service-details-grid .service-section[data-service='ST']").removeClass("bg-primary");

                    // Remove 'SP' and 'ST' from selected services if they are already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'SP' && service.service !== 'ST');
                }

                // Case 2: If 'SP' or 'ST' is selected and DC is already selected, clear DC selection
                if ((serviceName === 'SP' || serviceName === 'ST') && selectedServices.some(service => service.service ===
                        'DC')) {
                    // Deselect DC
                    $("#popupServices .service-details-grid .service-section[data-service='DC']").removeClass("bg-primary");

                    // Remove 'DC' from selected services
                    selectedServices = selectedServices.filter(service => service.service !== 'DC');
                }

                // Ensure AV and FS are mutually exclusive
                if (serviceName === 'AV') {
                    // Deselect 'FS' if 'AV' is selected
                    $("#popupServices .service-details-grid .service-section[data-service='FS']").removeClass("bg-primary");

                    // Remove 'FS' from selected services if it is already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'FS');
                }

                if (serviceName === 'FS') {
                    // Deselect 'AV' if 'FS' is selected
                    $("#popupServices .service-details-grid .service-section[data-service='AV']").removeClass("bg-primary");

                    // Remove 'AV' from selected services if it is already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'AV');
                }

                // Case 3: Handle WF, WI, and PL mutual exclusivity
                if (serviceName === 'WF') {
                    // Deselect 'WI' and 'PL' if 'WF' is selected
                    $("#popupServices .service-details-grid .service-section[data-service='WI']").removeClass("bg-primary");
                    $("#popupServices .service-details-grid .service-section[data-service='PL']").removeClass("bg-primary");

                    // Remove 'WI' and 'PL' from selected services if they are already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'WI' && service.service !== 'PL');
                }

                if (serviceName === 'WI') {
                    // Deselect 'WF' and 'PL' if 'WI' is selected
                    $("#popupServices .service-details-grid .service-section[data-service='WF']").removeClass("bg-primary");
                    $("#popupServices .service-details-grid .service-section[data-service='PL']").removeClass("bg-primary");

                    // Remove 'WF' and 'PL' from selected services if they are already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'WF' && service.service !== 'PL');
                }

                if (serviceName === 'PL') {
                    // Deselect 'WF' and 'WI' if 'PL' is selected
                    $("#popupServices .service-details-grid .service-section[data-service='WF']").removeClass("bg-primary");
                    $("#popupServices .service-details-grid .service-section[data-service='WI']").removeClass("bg-primary");

                    // Remove 'WF' and 'WI' from selected services if they are already selected
                    selectedServices = selectedServices.filter(service => service.service !== 'WF' && service.service !== 'WI');
                }

                // Toggle 'bg-primary' class for the selected service
                const isServiceSelected = selectedServices.some(service => service.service === serviceName);

                if (isServiceSelected) {
                    // If the service is already selected, remove it from the selectedServices array
                    selectedServices = selectedServices.filter(service => service.service !== serviceName);
                    $("#popupServices .service-details-grid .service-section[data-service='" + serviceName + "']").removeClass(
                        "bg-primary");
                } else {
                    // Add the service if it's not selected
                    selectedServices.push(selectedService);
                    $("#popupServices .service-details-grid .service-section[data-service='" + serviceName + "']").addClass(
                        "bg-primary");
                }

                updateInputs(); // Update the input fields with the selected services
            }

            document.addEventListener("DOMContentLoaded", function() {
                const $numberInput = $("#number");
                const $clientNameInput = $("#client_name");
                const $searchItemInput = $('#searchItem');
                const $searchData = $('#searchData .border');
                const $productItemError = $('#productItemError');

                // Debounce function to limit the rate of function execution
                function debounce(fn, delay) {
                    let timeoutId;
                    return function(...args) {
                        if (timeoutId) clearTimeout(timeoutId);
                        timeoutId = setTimeout(() => fn.apply(this, args), delay);
                    };
                }

                // Fetch client name when number input length is 10
                $numberInput.on("keyup", debounce(function() {
                    const clientNum = $(this).val().trim();

                    if (clientNum.length === 10) {
                        $.ajax({
                            url: "/admin/fetch-client-name",
                            method: "GET",
                            data: {
                                client_num: clientNum
                            },
                            success: (response) => {
                                if (response.success) {
                                    $clientNameInput.val(response.client_name);
                                } else {
                                    console.error(response.message);
                                }
                            },
                            error: (xhr, status, error) => console.error(
                                "Error fetching client name:", error),
                        });
                    } else if (clientNum.length < 10) {
                        $clientNameInput.val(''); // Clear the client name input
                    }
                }, 300)); // Debounce with 300ms delay

                // Search product items by name
                $searchItemInput.on('keyup', debounce(function() {
                    const searchValue = $(this).val().toLowerCase();

                    let visibleCount = 0;
                    $searchData.each(function() {
                        const productName = $(this).find('.searchProductName').data('name')
                            .toLowerCase();
                        const isVisible = productName.includes(searchValue);
                        $(this).toggle(isVisible);

                        if (isVisible) visibleCount++;
                    });

                    // Show or hide the error message based on visible product count
                    $productItemError.toggle(visibleCount === 0);
                }, 300)); // Debounce with 300ms delay
            });

            document.getElementById('addRightOdrbtn').addEventListener('click', function() {
                var itemName = document.getElementById('productName').value;
                var itemCategory = document.getElementById('productCategory').value;
                var itemService = document.getElementById('productservice').value;
                var itemQty = parseInt(document.getElementById('qtyPlsMns').value);
                var lundaryweight = parseInt(document.getElementById('lundaryweight').value) ?? 0;

                var itemPrice = parseFloat(document.getElementById('productprice').value);

                // Validate the inputs before proceeding
                if (!itemName) {
                    alert("Product name cannot be empty");
                    return;
                }
                if (!itemCategory) {
                    alert("Product category cannot be empty");
                    return;
                }
                if (!itemService) {
                    alert("Product service cannot be empty");
                    return;
                }
                if (isNaN(itemQty) || itemQty <= 0) {
                    alert("Quantity must be greater than zero");
                    return;
                }
                if (isNaN(itemPrice) || itemPrice <= 0) {
                    alert("Price must be greater than zero");
                    return;
                }
                if (itemName != "Laundry By Weight") {
                    clothtotal = itemQty * itemPrice;
                    unit = 'Unit';
                } else {
                    if (isNaN(lundaryweight) || lundaryweight <= 0) {
                        alert("Weight must be greater than zero");
                        return;
                    }
                    clothtotal = lundaryweight * itemPrice;
                    unit = 'Kg';
                }


                if (currentEditingRow) {
                    // Update the existing row
                    var existingQtyElement = currentEditingRow.querySelector('input[name="itemqty[]"]');
                    // var existingServiceElement = currentEditingRow.querySelector('#itemservice');
                    var existingPriceElement = currentEditingRow.querySelector('input[name="itemprice[]"]');
                    var existingTotalElement = currentEditingRow.querySelector('input[name="qtyxprice[]"]');

                    existingServiceElement.textContent = itemService;
                    existingPriceElement.textContent = itemPrice.toFixed(2); // Update the unit price
                    existingTotalElement.textContent = clothtotal.toFixed(2); // Update the total price

                    // Update hidden inputs
                    currentEditingRow.querySelector('input[name="itemqty[]"]').value = itemQty;
                    currentEditingRow.querySelector('input[name="clothlundaryqty[]"]').value = clothqty;
                    currentEditingRow.querySelector('input[name="itemprice[]"]').value = itemPrice.toFixed(2);
                    currentEditingRow.querySelector('input[name="qtyxprice[]"]').value = clothtotal.toFixed(2);

                    // Recalculate totals
                    updateTotals();

                    // Reset the current editing row after editing is done
                    currentEditingRow = null;
                } else {
                    // Check if an existing row with the same itemName and itemCategory exists
                    var existingRow = Array.from(document.querySelectorAll('.row')).find(row =>
                        row.dataset.itemName === itemName && row.dataset.itemCategory === itemCategory
                    );

                    if (existingRow) {
                        // If the item already exists, update the existing row
                        existingRow.querySelector('input[name="itemqty[]"]').value = itemQty;
                        existingRow.querySelector('input[name="clothlundaryqty[]"]').value = clothqty;
                        existingRow.querySelector('input[name="itemprice[]"]').value = itemPrice.toFixed(2);
                        existingRow.querySelector('input[name="qtyxprice[]"]').value = clothtotal.toFixed(2);

                        existingRow.querySelector('input[name="itemqty[]').textContent = clothqty;
                        existingRow.querySelector('input[name="itemprice[]"]').textContent = itemPrice.toFixed(2);
                        existingRow.querySelector('input[name="qtyxprice[]').textContent = clothtotal.toFixed(2);

                        // Recalculate totals
                        updateTotals();
                    } else {
                        // Handle the case for adding a new item (if item does not exist)
                        var rowDiv = document.createElement('div');
                        rowDiv.classList.add('row', 'border');
                        rowDiv.dataset.itemName = itemName;
                        rowDiv.dataset.itemCategory = itemCategory;

                        // Update the quantity, service, price, and total in the row
                        if (itemName != "Laundry By Weight") {
                            clothqty = itemQty;
                        } else {
                            clothqty = lundaryweight;
                        }

                        let productDetailsHTML = '';
                        allProductDetail.forEach(function(product) {
                            productDetailsHTML += `
                                <div class="product-detail">
                                    <p>Product: ${product.productName}, Qty: ${product.quantity}, Category: ${product.categoryId}</p>
                                    <input type="hidden" value="${product.productName}" name="lundaryProductName[]">
                                    <input type="hidden" value="${product.quantity}" name="lundaryProductQty[]">
                                    <input type="hidden" value="${product.categoryId}" name="lundaryProductCategroyId[]">
                                </div>
                            `;
                        });

                        // Create the main rowDiv with product details included
                        rowDiv.innerHTML = `
                                <div class="col-md-1">
                                    <i class="fa fa-trash remove-item" aria-hidden="true"></i>
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" name="itemname[]" value="${itemName}">
                                    <input type="hidden" name="itemcategory[]" value="${itemCategory}">
                                    <input type="hidden" name="itemservice[]" value="${itemService}">
                                    <input type="hidden" name="itemqty[]" value="${itemQty}">
                                    <input type="hidden" name="clothlundaryqty[]" value="${clothqty}">
                                    <input type="hidden" name="itemprice[]" value="${itemPrice.toFixed(2)}">
                                    <input type="hidden" name="qtyxprice[]" value="${clothtotal.toFixed(2)}">
                                    <input type="hidden" name="unit[]" value="${unit}">
                                    <p><span id="itemname">${itemName}</span> <span id="itemcategory">(${itemCategory})</span></p>
                                    ${itemName == "Laundry By Weight" ? productDetailsHTML : ""}
                                    <p>Service: (<span id="itemservice">${itemService}</span>)</p>
                                </div>
                                <div class="col-md-3">
                                    <p><span id="itemqty[]">${clothqty}</span> x <span id="itemprice[]">${itemPrice.toFixed(2)}</span> = <span id="qtyxprice[]">${clothtotal.toFixed(2)}</span></p>
                                </div>
                            `;

                        document.querySelector('.col-xl-12').appendChild(rowDiv);
                    }
                }


                // Add the item to totals
                addItemToTotals(itemName, itemQty, itemPrice, itemService, itemCategory);



                // selectedGarments.appendChild(garmentDiv);

                resetOffcanvasInputs(); // Reset input fields after adding/updating

                var offcanvasElement = document.getElementById('offcanvasRight');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (!offcanvasInstance) {
                    offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
                }
                offcanvasInstance.hide();
            });



            // Function to reset the input fields and clear offcanvas content
            function resetOffcanvasInputs() {

                var itemRow = event.target.closest('#selectedGarmentsList');
                console.log(itemRow);


                document.getElementById('productName').value = '';
                document.getElementById('productCategory').value = '';
                document.getElementById('productservice').value = '';
                document.getElementById('qtyPlsMns').value = 0;
                document.getElementById('productprice').value = '';
                document.getElementById('lundaryweight').value = '',

                    // Reset selected services and categories
                    selectedServices = [];

                // Clear the categories and services section
                document.getElementById('popupCategories').innerHTML = '';
                document.getElementById('popupServices').innerHTML = '';
                document.getElementById('selectedGarmentsList').innerHTML = '';

                if (itemRow) {
                    // Clear input feild
                    itemRow.querySelector('input[name="productName_"]').value = '';
                    itemRow.querySelector('input[name="quantity_"]').value = '';
                    itemRow.querySelector('input[name="categoryId_"]').value = '';
                } else {
                    console.error('itemRow is null or not found in the DOM');
                }
            }

            let currentEditingRow = null; // Variable to keep track of the row being edited

            // Open the edit popup and track the current row
            function openEditItemPopup(itemName, itemCategory, itemService, itemQty, itemPrice, rowElement) {
                currentEditingRow = rowElement; // Store the row that is being edited

                // Populate the form fields with the selected item's details
                document.getElementById('productName').value = itemName;
                document.getElementById('productCategory').value = itemCategory;
                document.getElementById('productservice').value = itemService;
                document.getElementById('qtyPlsMns').value = itemQty;
                document.getElementById('productprice').value = itemPrice.toFixed(2);

                // Pre-select the category in the popup
                var categoryBadges = document.querySelectorAll('#popupCategories .subcategory');
                categoryBadges.forEach(function(badge) {
                    if (badge.textContent.trim() === itemCategory) {
                        badge.classList.add('bg-primary'); // Highlight the selected category
                        selectPopupCategory(badge, itemCategory, rowElement.getAttribute(
                            'data-product-id')); // Load services for this category
                    } else {
                        badge.classList.remove('bg-primary'); // Remove highlight for other categories
                    }
                });

                // Pre-select the services
                var serviceArray = itemService.split(',').map(service => service.trim());
                var serviceSections = document.querySelectorAll('#popupServices .service-section');
                serviceSections.forEach(function(section) {
                    var serviceName = section.getAttribute('data-service');
                    if (serviceArray.includes(serviceName)) {
                        section.classList.add('bg-primary'); // Highlight the selected service
                    } else {
                        section.classList.remove('bg-primary'); // Remove highlight for other services
                    }
                });

                // Open the offcanvas for editing
                var offcanvasElement = document.getElementById('offcanvasRight');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (!offcanvasInstance) {
                    offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
                }
                offcanvasInstance.show();
            }



            // Add click event listener for the "Add Garment" button
            document.getElementById('addGarmentBtn').addEventListener('click', function() {
                // Step 2: Find all product items except "Laundry By Weight"
                var filteredItems = groupedProductItems.filter(function(item) {
                    return !item.grouped_details.hasOwnProperty('Laundry By Weight');
                });

                // Get the modal body where items will be displayed
                var availableItemsList = document.getElementById('availableItemsList');
                availableItemsList.innerHTML = ''; // Clear previous content

                // Populate the modal with filtered items
                filteredItems.forEach(function(item) {
                    var itemElement = document.createElement('div');
                    itemElement.classList.add('border', 'rounded', 'p-2', 'mb-2', 'col-md-4', 'ml-2');

                    // Create a string to show categories from grouped_details, with the first one having 'bg-primary'
                    var categoriesHtml = '';
                    var categories = Object.keys(item.grouped_details); // Get the category keys

                    // Initialize laundaryItemcategorystore to store the first category
                    var laundaryItemcategorystore = '';

                    // Loop through the categories
                    categories.forEach(function(categoryKey, index) {
                        // Split the category string by comma to form an array
                        var categoryArray = categoryKey.split(',');

                        // Get the first value from the category array
                        var firstCategory = categoryArray[0];

                        // Store the first category in laundaryItemcategorystore (only once)
                        if (index === 0) {
                            laundaryItemcategorystore = firstCategory;
                        }

                        // If it's the first category, give it the 'bg-primary' class; otherwise, 'bg-secondary'
                        var badgeClass = index === 0 ? 'bg-primary' : 'bg-secondary';

                        categoriesHtml += `<span
                                                onclick="laundarySelectCategory(this, '${firstCategory}', '${item.product_item.id}')"
                                                class="badge mb-2 mr-2 laundarysubcategory ${badgeClass}"
                                                id="category-${item.product_item.id}-${firstCategory}" style="margin-left: 2%;">${firstCategory}
                                            </span>`;
                    });

                    // Hidden input storing laundaryItemcategorystore value (moved outside the loop)
                    var laundaryItemcategorystorehtml =
                        `<input type="hidden" name="laundaryItemcategorystore" id="laundaryItemcategory-${item.product_item.id}" value="${laundaryItemcategorystore}">`;

                    // Display item name, image, and categories in the modal
                    itemElement.innerHTML = `
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-2 text-dark searchProductName" data-name="${item.product_item.name}">${item.product_item.name}</h6>
                                <input type="hidden" name="laundaryItemName" id="laundaryItemName" value="${item.product_item.name}">
                                ${laundaryItemcategorystorehtml}
                                <div id="laundarycategories-${item.product_item.id}" class="category-section mb-3">
                                    ${categoriesHtml}
                                </div>
                                <div class="input-group mb-3">
                                    <button type="button" class="input-group-text decrease"><i class="fa-solid fa-minus"></i></button>
                                    <input type="text" class="form-control text-center piece-count" value="0" id="laundaryqtyPlsMns-${item.product_item.id}" name="laundaryqtyPlsMns" placeholder="0">
                                    <button type="button" class="input-group-text increase"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    `;

                    availableItemsList.appendChild(itemElement);
                });

                // Step 3: Show the garmentItemsModal modal
                var garmentItemsModal = new bootstrap.Modal(document.getElementById('garmentItemsModal'));
                garmentItemsModal.show();
            });



            // Get references to the elements
            const qtyInput = document.getElementById("qtyPlsMns");
            const increaseBtn = document.querySelector(".increase");
            const decreaseBtn = document.querySelector(".decrease");

            // Increase button click event
            increaseBtn.addEventListener("click", function() {
                let currentValue = parseInt(qtyInput.value);
                if (!isNaN(currentValue)) {
                    qtyInput.value = currentValue + 1; // Increment value by 1
                }
            });

            // Decrease button click event
            decreaseBtn.addEventListener("click", function() {
                let currentValue = parseInt(qtyInput.value);
                if (!isNaN(currentValue) && currentValue > 0) {
                    qtyInput.value = currentValue - 1; // Decrement value by 1 (but not below 0)
                }
            });


            // Use event delegation for increase and decrease buttons
            document.getElementById('availableItemsList').addEventListener('click', function(event) {
                if (event.target.closest('.increase')) {
                    // Find the quantity input related to this increase button
                    let qtyInput = event.target.closest('.row').querySelector('.piece-count');
                    let currentValue = parseInt(qtyInput.value);
                    if (!isNaN(currentValue)) {
                        qtyInput.value = currentValue + 1; // Increment value by 1
                    }
                }

                if (event.target.closest('.decrease')) {
                    // Find the quantity input related to this decrease button
                    let qtyInput = event.target.closest('.row').querySelector('.piece-count');
                    let currentValue = parseInt(qtyInput.value);
                    if (!isNaN(currentValue) && currentValue > 0) {
                        qtyInput.value = currentValue - 1; // Decrement value by 1 (but not below 0)
                    }
                }
            });
            // Function for handling category selection and updating the UI
            function laundarySelectCategory(element, category, productId) {
                // Find the category section for the given productId
                var categorySection = document.querySelector('#laundarycategories-' + productId);

                // Get all the category badges in the section
                var categorysBadges = categorySection.querySelectorAll('.laundarysubcategory');


                // Check if categorysBadges has any elements
                if (categorysBadges.length === 0) {
                    console.error('No category badges found for productId:', productId);
                    return; // Exit if no badges are found
                }

                // Create an array to store category names
                var categoryNames = [];

                // Populate the categoryNames array with the names from the badges
                categorysBadges.forEach(function(badge) {
                    var categoryName = badge.id.split('-').pop(); // Get the category name from the ID
                    categoryNames.push(categoryName); // Add the category name to the array
                });


                // Check if the selected category exists in the array
                if (categoryNames.includes(category)) {
                    // If the category exists, iterate through the badges and update classes
                    categorysBadges.forEach(function(badge) {
                        var categoryName = badge.id.split('-').pop(); // Get the category name from the ID

                        if (categoryName == category) {
                            // If the names match, add the 'bg-primary' class (make it active)
                            badge.classList.remove('bg-secondary');
                            badge.classList.add('bg-primary');
                        } else {
                            // If names don't match, revert to 'bg-secondary' (deactivate)
                            badge.classList.remove('bg-primary');
                            badge.classList.add('bg-secondary');
                        }
                    });
                } else {
                    alert('Category not found in the array!'); // Alert if the category isn't in the array
                }

                // Set the value of laundaryItemcategory (hidden input field)
                var laundaryItemCategoryInput = document.getElementById('laundaryItemcategory-' + productId);
                laundaryItemCategoryInput.value = category; // Set the selected category value
            }

            // Attach click event listener to category badges after DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.subcategory').forEach(function(categoryBadge) {
                    categoryBadge.addEventListener('click', function() {
                        // Call the laundarySelectCategory function with the proper parameters
                        var category = this.textContent.trim(); // Get the clicked category's text
                        var productId = this.getAttribute(
                            'data-product-id'); // Get the productId from data attribute
                        laundarySelectCategory(this, category, productId);
                    });
                });
            });

            document.getElementById('addOrderFormValidation').addEventListener('submit', function(event) {
                let hasError = false;

                // Client number validation
                const clientNumber = document.getElementById('number').value.trim();
                if (clientNumber.length !== 10 || isNaN(clientNumber)) {
                    alert("Please enter a valid 10-digit client number.");
                    hasError = true;
                }

                // Client name validation
                const clientName = document.getElementById('client_name').value.trim();
                if (clientName.length < 2 || clientName.length > 20) {
                    alert("Client name must be between 2 and 20 characters.");
                    hasError = true;
                }

                // Item validation (check if at least one item is added)
                const items = document.querySelectorAll('.row.border'); // Assuming this is the item row class
                if (items.length === 0) {
                    alert("Please add at least one item.");
                    hasError = true;
                } else {
                    items.forEach(item => {
                        const itemName = item.querySelector('#itemname').textContent.trim();
                        const itemQty = item.querySelector('#itemqty[]').textContent.trim();
                        const itemPrice = item.querySelector('#itemprice[]').textContent.trim();

                        if (!itemName) {
                            alert("Item name cannot be empty.");
                            hasError = true;
                        }
                        if (parseInt(itemQty) <= 0) {
                            alert("Quantity must be at least 1.");
                            hasError = true;
                        }
                        if (parseFloat(itemPrice) <= 0) {
                            alert("Price must be a positive number.");
                            hasError = true;
                        }
                    });
                }

                if (hasError) {
                    event.preventDefault(); // Prevent form submission if any validation failed
                }
            });

            // Function to update totals after removing an item
            function removeItemFromTotals(itemQty, itemPrice) {
                // Update global totals
                totalQty -= itemQty;
                grossTotal -= itemPrice;
                totalAmount -= itemPrice;

                // Ensure values don't drop below 0
                if (totalQty < 0) totalQty = 0;
                if (grossTotal < 0) grossTotal = 0;
                if (totalAmount < 0) totalAmount = 0;

                // Update the totals in the DOM
                updateTotals();
            }

            // Ensure event delegation works properly for dynamically added "remove-item" buttons
            document.addEventListener('click', function(event) {
                // Check if the clicked element is the remove button or contains the remove-item class
                if (event.target.closest('.remove-item')) {
                    // Get the closest row with the class 'border' (the item to be removed)
                    var itemRow = event.target.closest('.row.border');

                    // If the itemRow is found, proceed with removing the item
                    if (itemRow) {
                        // Get the item quantity and price from the DOM
                        var itemQty = parseInt(itemRow.querySelector('input[name="itemqty[]"]').value);;
                        var itemPrice = parseFloat(itemRow.querySelector('input[name="qtyxprice[]"]').textContent);

                        // Remove the item row from the DOM
                        itemRow.remove();

                        // Update the totals
                        removeItemFromTotals(itemQty, itemPrice);
                    }
                }
            });

            // Event listener for dynamically added "edit-item" buttons
            // document.addEventListener('click', function(event) {
            //     if (event.target.classList.contains('edit-item')) {
            //         // Get the details of the item that needs to be edited
            //         var itemRow = event.target.closest('.row.border');
            //         var itemName = itemRow.querySelector('#itemname').textContent.trim();
            //         var itemCategory = itemRow.querySelector('#itemcategory').textContent.trim();
            //         var itemService = itemRow.querySelector('#itemservice').textContent.trim();
            //         var itemQty = parseInt(itemRow.querySelector('#itemqty').textContent.trim());
            //         var itemPrice = parseFloat(itemRow.querySelector('#itemprice').textContent.trim());

            //         // Open the edit popup with the current item's details and row reference
            //         openEditItemPopup(itemName, itemCategory, itemService, itemQty, itemPrice, itemRow);
            //     }
            // });

            document.getElementById("qtyPlsMns").addEventListener("input", function(e) {
                // Remove non-digit characters and limit input to 4 digits
                const value = e.target.value.replace(/\D/g, "").slice(0, 4);
                e.target.value = value;
            });

            // Add another event listener to the "Add" button to collect data based on `laundaryqtyPlsMns`
            document.getElementById('addLaundaryItem').addEventListener('click', function() {
                var itemsWithNonZeroQty = [];
                var totalQuantity = 0; // Variable to store the total quantity

                // Select all inputs with the name 'laundaryqtyPlsMns'
                var qtyInputs = document.querySelectorAll('input[name="laundaryqtyPlsMns"]');


                qtyInputs.forEach(function(input) {
                    var qty = parseInt(input.value, 10); // Get the quantity from input
                    if (qty > 0) {
                        // Find the associated product item and add to the array
                        var productName = input.closest('.col-md-8').querySelector('h6.searchProductName')
                            .getAttribute('data-name');
                        var categoryId = input.closest('.col-md-8').querySelector(
                            'input[name="laundaryItemcategorystore"]').value;

                        // Push item to the array
                        itemsWithNonZeroQty.push({
                            productName: productName,
                            quantity: qty,
                            categoryId: categoryId
                        });

                        // Increment the total quantity
                        totalQuantity += qty;

                        document.getElementById("qtyPlsMns").value = totalQuantity;

                    }
                });

                // Example of safely appending a child
                var selectedGarmentsList = document.getElementById('selectedGarmentsList');

                if (selectedGarmentsList) {
                    selectedGarmentsList.innerHTML = ''; // Clear previous content

                    // Make sure itemsWithNonZeroQty exists and has content
                    if (itemsWithNonZeroQty.length > 0) {
                        itemsWithNonZeroQty.forEach(function(item, index) {
                            allProductDetail.push(item);
                            var itemElement = document.createElement('div');
                            itemElement.classList.add('border', 'p-2', 'mb-2');

                            // Display item name, quantity, and category
                            itemElement.innerHTML = `
                                <h6>${item.productName}</h6>
                                <p>Quantity: ${item.quantity}</p>
                                <p>Category: ${item.categoryId}</p>

                                <!-- Hidden input fields -->
                                <input type="hidden" name="productName_${index}" value="${item.productName}">
                                <input type="hidden" name="quantity_${index}" value="${item.quantity}">
                                <input type="hidden" name="categoryId_${index}" value="${item.categoryId}">
                            `;

                            // Append the newly created item
                            selectedGarmentsList.appendChild(itemElement);
                        });

                        // Show the offcanvas modal
                        var offcanvasRight = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
                        offcanvasRight.show();

                        // Close the garmentItemsModal after showing the offcanvas modal
                        var garmentItemsModal = bootstrap.Modal.getInstance(document.getElementById(
                            'garmentItemsModal'));
                        garmentItemsModal.hide();
                    } else {
                        console.log("No items with non-zero quantity.");
                    }
                } else {
                    console.error("Error: selectedGarmentsList element not found.");
                }
                console.log("Test ------------------- ", allProductDetail)
            });
        </script>

    @endsection
