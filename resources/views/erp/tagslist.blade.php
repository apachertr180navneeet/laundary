@extends('erp.layouts.app')
@section('content')

<!-- Adding custom styles for table items -->
<style>
    .table-item-container {
        width: 300px; /* Set fixed width for each item */
        display: inline-block; /* Ensures that items are aligned horizontally */
    }

    .table-item {
        box-sizing: border-box;
        padding: 10px;
        background: #ffffffb3; /* Slight transparent white background */
        border: 1px solid #dbdade; /* Light border */
        border-radius: 5px; /* Rounded corners */
        text-align: center; /* Center align text */
        vertical-align: top; /* Align vertically to the top */
    }

    .table-item div {
        color: black;
        border-radius: 5px;
    }

    /* Style for the print button */
    .print-button {
        display: block;
        width: 100%; /* Full width */
        text-align: center;
        margin: 20px 0; /* Adds margin to the top and bottom */
    }

    /* Button styles */
    .print-button button {
        color: black;
        border: none;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer; /* Change cursor on hover */
    }
</style>

<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp Add_order_page_section">
            <div class="card">
                <div class="card-body">

                    <!-- Back button and print button -->
                    <div class="row justify-content-between mb-3">
                        <div class="col-lg-3">
                            <!-- Back to view-order page -->
                            <a type="button" class="text-primary" href="{{ url('/erp/view-order') }}">
                                <i class="fa-solid fa-arrow-left me-2"></i> Tags
                            </a>
                        </div>
                        <div class="col-lg-1">
                            <!-- Print tag list -->
                            <a class="btn btn-primary" href="{{ url('/erp/print-taglist/' . $order->id) }}" type="button">
                                <i class="fa-solid fa-print me-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Table container starts -->
                    <div class="table-container">
                        @php
                            $counter = 0; // Initialize counter to keep track of items
                        @endphp

                        <!-- Loop through each order item -->
                        @foreach ($order->orderItems as $orderItem)
                            @php
                                $service = $orderItem->operation_id; // Get the service (operation id) for each item
                            @endphp

                            <!-- Check if the item is Shoe, Gloves, or Socks/Stocking Pair -->
                            @if($orderItem->Item->name == 'Shoe' || $orderItem->Item->name == 'Gloves' || $orderItem->Item->name == 'Socks/Stocking Pair'|| $orderItem->Item->name == 'Slip on')
                                @for ($i = 0; $i < $orderItem->quantity * 2; $i++) <!-- Multiply by 2 as required -->
                                    @if ($counter % 3 == 0)
                                        <div class="table-row"> <!-- Open a new row every 3 items -->
                                    @endif

                                    <!-- Table item starts -->
                                    <div class="table-item-container">
                                        <div class="table-item text-center">
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">Mega Dry Cleaning</p>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;text-transform: capitalize;">{{ $order->user->name }}</p>
                                            <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->order_number }}</p>
                                            <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->delivery_date }}</p>
                                            <div><span>T {{ $subTotalqty }}</span></div>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; border: 1px solid #000; width:100px; height: 30px;display: flex; align-items: center; justify-content: center; border-radius: 5px;margin: 5px auto;">{{ $service }}</p>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->Item->name }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $counter++; // Increment counter
                                    @endphp
                                    @if ($counter % 3 == 0)
                                        </div> <!-- Close row after every 3 items -->
                                    @endif
                                @endfor
                            <!-- Check if the item is Laundry By Weight -->
                            @elseif($orderItem->Item->name == 'Laundry By Weight')


                                    @php
                                        // Fetch related laundry items with categories and names for each quantity
                                        $lundaryItems = DB::table('lundary_order_item')
                                            ->join('items', 'lundary_order_item.ProductName', '=', 'items.id')
                                            ->join('categories', 'lundary_order_item.ProductCategroyId', '=', 'categories.id')
                                            ->select('items.name as item_name', 'categories.name as category_name', 'lundary_order_item.ProductQty as ProductQty')
                                            ->where('lundary_order_item.order_item_id', $order->id)
                                            ->get();
                                    @endphp

                                    @foreach ($lundaryItems as $lundaryItem)
                                        @for ($i = 0; $i < $lundaryItem->ProductQty	; $i++) <!-- Loop based on quantity -->
                                        @if ($counter % 3 == 0)
                                            <div class="table-row"> <!-- Open a new row every 3 items -->
                                        @endif
                                        <div class="table-item-container">
                                            <div class="table-item text-center">
                                                <p style="font-weight: bold; font-size: 14px; color: #6c757d;">Mega Dry Cleaning</p>
                                                <p style="font-weight: bold; font-size: 14px; color: #6c757d;text-transform: capitalize;">{{ $order->user->name }}</p>
                                                <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->order_number }}</p>
                                                <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->delivery_date }}</p>
                                                <div><span>T {{ $subTotalqty }}</span></div>
                                                <p style="font-weight: bold; font-size: 14px; color: #6c757d; border: 1px solid #000; width:100px; height: 30px;display: flex; align-items: center; justify-content: center; border-radius: 5px;margin: 5px auto;">{{ $service }}</p>
                                                <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $lundaryItem->item_name }}</p>
                                            </div>
                                        </div>
                                        @php
                                            $counter++; // Increment counter
                                        @endphp
                                        @if ($counter % 3 == 0)
                                            </div> <!-- Close row after every 3 items -->
                                        @endif
                                        @endfor
                                    @endforeach

                            <!-- For other items -->
                            @else
                                @for ($i = 0; $i < $orderItem->quantity; $i++) <!-- Loop based on quantity -->
                                    @if ($counter % 3 == 0)
                                        <div class="table-row"> <!-- Open a new row every 3 items -->
                                    @endif
                                    <div class="table-item-container">
                                        <div class="table-item text-center">
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">Mega Dry Cleaning</p>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;text-transform: capitalize;">{{ $order->user->name }}</p>
                                            <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->order_number }}</p>
                                            <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">{{ $order->delivery_date }}</p>
                                            <div><span>T {{ $subTotalqty }}</span></div>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; border: 1px solid #000; width:100px; height: 30px;display: flex; align-items: center; justify-content: center; border-radius: 5px;margin: 5px auto;">{{ $service }}</p>
                                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->Item->name }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $counter++; // Increment counter
                                    @endphp
                                    @if ($counter % 3 == 0)
                                        </div> <!-- Close row after every 3 items -->
                                    @endif
                                @endfor
                            @endif
                        @endforeach

                        <!-- Ensure the last row is closed properly -->
                        @if ($counter % 3 != 0)
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


