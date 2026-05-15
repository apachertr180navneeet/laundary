@extends('backend.layouts.app')
@section('content')
<style>
    noDataMessage {
        color: red;
        font-weight: bold;
        padding: 10px;
        border: 1px solid red;
        border-radius: 5px;
        background-color: #f8d7da; /* Light red background */
        display: none; /* Initially hidden */
    }
</style>
<div class="layout-page mt-4">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Dashboard Section -->
                <div class="col-md-6 mb-2"> Analytics Dashboard </div>

                <div class="col-md-6 mb-2 text-end">
                    <button type="button" class="btn btn-primary" id="downloadExcel">Excel Download</button>
                </div>

                <!-- Total Orders -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-primary p-1">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <h6 class="mb-0">Total Orders</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="totalOrders"></h3>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-success p-1">
                                    <i class="tf-icons fa-solid fa-check-double"></i>
                                </div>
                                <h6 class="mb-0">Completed Orders</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="deliveredOrders"></h3>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-warning p-1">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <h6 class="mb-0">Pending Orders</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="pendingOrders"></h3>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-info p-1"> ₹ </div>
                                <h6 class="mb-0">Total Amount</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="totalOrdersAmount">₹ </h3>
                        </div>
                    </div>
                </div>

                <!-- Tax -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-info p-1"> ₹ </div>
                                <h6 class="mb-0">Tax</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="taxAmount">₹ </h3>
                        </div>
                    </div>
                </div>

                <!-- Taxable Amount -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge badge-kj rounded bg-label-info p-1"> ₹ </div>
                                <h6 class="mb-0">Taxable Amount</h6>
                            </div>
                            <h3 class="my-2 pt-1 text-end" id="grossTotal">₹ </h3>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row justify-content-between">
                                <div class="col-md-4 col-lg-4 col-xl-4"> Customers</div>
                                <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input type="search" class="form-control" placeholder="Search..." id="invoiceSearch">
                                        </div>
                                        <div class="col-lg-3">
                                            <select id="monthDropdown" class="form-control">
                                                <option value="">Select Month</option>
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="text" class="form-control" id="startDate" placeholder="Start Date" onfocus="(this.type='date')" onblur="(this.type='text')">
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="text" class="form-control" id="endDate" placeholder="End Date" onfocus="(this.type='date')" onblur="(this.type='text')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="ordersTable">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Order ID</th>
                                            <th>Name</th>
                                            <th>Booking Date</th>
                                            <th>Status</th>
                                            <th>Total Amount</th>
                                            <th>Tax</th>
                                            <th>Taxable Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ordersData"></tbody>
                                </table>
                                <div id="noDataMessage" class="text-center" style="display: none;">
                                    No data for the selected filter.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function () {
            function fetchFilteredData() {
                var search = $('#invoiceSearch').val();
                var date = $('#filterDate').val();
                var month = $('#monthDropdown').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();


                $.ajax({
                    url: '{{ route("analytics.filter") }}',
                    method: 'GET',
                    data: {
                        search: search,
                        date: date,
                        month: month,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function (response) {

                        // Update dashboard counts
                        $('#totalOrders').text(response.totalOrders);
                        $('#pendingOrders').text(response.pendingOrders);
                        $('#deliveredOrders').text(response.deliveredOrders);
                        $('#totalOrdersAmount').text('₹ ' + response.totalOrdersAmount.toFixed(2));
                        $('#taxAmount').text('₹ ' + response.taxAmount.toFixed(2));
                        $('#grossTotal').text('₹ ' + response.grossTotal.toFixed(2));

                        // Update table data
                        $('#ordersData').html('');
                        if (response.orders.length > 0) {
                            $.each(response.orders, function (index, order) {
                                // Determine the status badge
                                var statusBadge = '';
                                if (order.status === 'pending') {
                                    statusBadge = '<div class="badge rounded bg-label-warning py-1">Pending</div>';
                                } else if (order.status === 'delivered') {
                                    statusBadge = '<div class="badge rounded bg-label-success py-1">Delivered</div>';
                                } else {
                                    statusBadge = '<div class="badge rounded bg-label-secondary py-1">' + order.status + '</div>'; // In case there are other statuses
                                }

                                let totalAmount = Number(order.total_amount).toFixed(2);

                                $('#ordersData').append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td><a href="/admin/show-order/${order.id}" class="btn Client_table_action_icon px-2">${order.order_number}</a></td>
                                        <td>${order.name}</td>
                                        <td>${order.order_date}</td>
                                        <td>${statusBadge}</td>
                                        <td>₹ ${totalAmount}</td>
                                        <td>₹ ${order.total_amount ? ((order.total_amount) - (Number(order.total_amount) / 1.18)).toFixed(2) : '0.00'}</td>
                                        <td>₹ ${order.total_amount ? (Number(order.total_amount) / 1.18).toFixed(2) : '0.00'}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            $('#ordersData').append('<tr><td colspan="8">No data for the selected filters.</td></tr>');
                        }
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Fetch filtered data when search, date, or month is changed
            $('#invoiceSearch, #filterDate, #monthDropdown, #startDate, #endDate').on('change keyup', function () {
                fetchFilteredData();
            });

            // Initial load
            fetchFilteredData();

            // Excel Download Function
            $('#downloadExcel').click(function () {
                let table = document.getElementById("ordersTable");
            
                // Clone the table to modify before exporting
                let tableClone = table.cloneNode(true);
                let rows = tableClone.querySelectorAll("tr");

                // Remove the 6th and 7th columns (Tax and Taxable Amount)
                rows.forEach(row => {
                    let cells = row.querySelectorAll("td, th");
                    if (cells.length >= 7) {
                        // Remove the Tax and Taxable Amount columns (6th and 7th)
                        cells[6].remove(); // Tax column (index 5)
                        cells[7].remove(); // Taxable Amount column (index 6)
                    }
                });
            
                let totalAmountSum = 0; // Variable to store the total sum
            
                // Loop through rows to calculate the sum
                rows.forEach((row, index) => {
                    const cells = row.querySelectorAll("td");
            
                    if (cells.length > 5) {
                        const totalAmountCell = cells[5]; // Assuming Total Amount is in the 6th column (index 5)
            
                        // Extract and clean up the total amount
                        let totalAmount = totalAmountCell ? totalAmountCell.innerText.trim() : "0";
                        totalAmount = totalAmount.replace(/[^\d.-]/g, ""); // Remove ₹ and other non-numeric characters
            
                        if (!isNaN(totalAmount) && totalAmount !== "") {
                            totalAmount = parseFloat(totalAmount); // Convert to number
                            totalAmountSum += totalAmount; // Add to total sum
                        }
                    }
                });
            
                // Add a new row for the total sum
                let newRow = tableClone.insertRow();
                for (let i = 0; i < 6; i++) {
                    if (i === 4) {
                        // Add "Total Amount" label to the 4th column
                        newRow.insertCell(i).innerText = "Total Amount";
                    } else if (i === 5) {
                        // Add the total sum to the 6th column
                        let totalCell = newRow.insertCell(i);
                        totalCell.innerText = "₹ " + totalAmountSum.toFixed(2);
                        totalCell.style.fontWeight = "bold"; // Make it bold
                    } else {
                        // Empty cells for alignment
                        newRow.insertCell(i).innerText = "";
                    }
                }
            
                // Create a workbook from the modified table
                let wb = XLSX.utils.book_new();
                let ws = XLSX.utils.table_to_sheet(tableClone, { raw: true });
                XLSX.utils.book_append_sheet(wb, ws, "Orders Data");
            
                // Export the file
                XLSX.writeFile(wb, "Orders_Data.xlsx");
            });                   
        });
    </script>
@endsection
