<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: 144pt 187pt; /* 2x2.6 inches */
            margin: 0; /* Remove all margins */
        }

        body {
            margin: 0;
            padding: 0;
            width: 144pt; /* 2 inches */
            height: 187pt; /* 2.6 inches */
            overflow: hidden; /* Ensure no overflow */
        }

        .table-item-container {
            width: 144pt; /* Match label width */
            height: 187pt; /* Match label height */
            box-sizing: border-box;
            border: 1px solid #dbdade;
            display: block;
            padding: 0; /* Ensure this is not causing extra space */
            margin: 0; /* Ensure there is no additional space between tags */
            border-radius: 5pt; /* Optional: Adjust or remove if necessary */
            page-break-inside: avoid;
            text-align: center;
            box-sizing: border-box;
        }

        .table-item {
            text-align: center;
        }

        .table-item p {
            margin: 2pt auto; /* Adjust margins as needed */
            font-size: 10pt; /* Ensure font size fits well within tag */
            color: black;
            width: 100%;
        }
    </style>
</head>

<body>
    @php
        $counter = 0; // Initialize counter to keep track of items
    @endphp
    @foreach ($order->orderItems as $orderItem)
        @php
            $services = $orderItem->operation_id;
        @endphp
        @if($orderItem->Item->name == 'Shoe' || $orderItem->Item->name == 'Gloves' || $orderItem->Item->name == 'Socks/Stocking Pair'|| $orderItem->Item->name == 'Slip on')
            @for ($i = 0; $i < $orderItem->quantity * 2; $i++)
                <div class="table-item-container"  style=" border: 2px dashed #000; border-radius: 5px; margin: 2px;width:48mm; height: 63mm;">
                    <div class="table-item text-center">
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;">Mega Dry Cleaning</p>
                        <p style="font-weight: bolder; font-size: 18px; color: black; margin-bottom:10px; margin-top: 12px;">{{ $order->order_number }}</p>
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;text-transform: capitalize;">{{ $order->user->name }}</p>
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                        <div style="margin-bottom:5px">
                            <span style="padding:10px 25px; font-weight: 900; font-size: 14px;">T {{ $subTotalqty }}</span>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; color: black; border: 1px solid #000;width:100px; padding: 5px 0 ;border-radius: 5px;margin: 7px auto;">
                            @if($orderItem->operation_id)
                                {{ $services }}
                            @else
                                Operation data missing
                            @endif
                        </p>
                        @if($orderItem->Item && $orderItem->categories)
                            @if($laundryOrderItem && $orderItem->Item->name == 'Laundry By Weight')
                                <p style="font-weight: bold; font-size: 12px; color: black;">{{ $laundryOrderItem->Item->name }}/{{ $laundryOrderItem->categories->name }}</p>
                                {{-- <p style="font-weight: bold; font-size: 12px; color: black;">{{ $laundryOrderItem->categories->name }}</p> --}}
                            @else
                                <p style="font-weight: bold; font-size: 12px; color: black;">{{ $orderItem->Item->name }}</p>
                            @endif
                        @else
                            Product or Category data missing
                        @endif
                    </div>
                </div>
            @endfor
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
                    <div class="table-item-container"  style=" border: 2px dashed #000; border-radius: 5px; margin: 2px;width:48mm; height: 63mm;">
                        <div class="table-item text-center">
                            <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;">Mega Dry Cleaning</p>
                            <p style="font-weight: bolder; font-size: 18px; color: black; margin-bottom:10px; margin-top: 12px;">{{ $order->order_number }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;text-transform: capitalize;">{{ $order->user->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                            <div style="margin-bottom:5px">
                                <span style="padding:10px 25px; font-weight: 900; font-size: 14px;">T {{ $subTotalqty }}</span>
                            </div>
                            <p style="font-weight: bold; font-size: 14px; color: black; border: 1px solid #000;width:100px; padding: 5px 0 ;border-radius: 5px;margin: 7px auto;">
                                @if($orderItem->operation_id)
                                    {{ $services }}
                                @else
                                    Operation data missing
                                @endif
                            </p>
                            <p style="font-weight: bold; font-size: 12px; color: black;">{{ $lundaryItem->item_name }}</p>
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
        @else
            @for ($i = 0; $i < $orderItem->quantity; $i++)
                <div class="table-item-container"  style=" border: 2px dashed #000; border-radius: 5px; margin: 2px;width:48mm; height: 63mm;">
                    <div class="table-item text-center">
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;">Mega Dry Cleaning</p>
                        <p style="font-weight: bolder; font-size: 18px; color: black; margin-bottom:10px; margin-top: 12px;">{{ $order->order_number }}</p>
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 12px;text-transform: capitalize;">{{ $order->user->name }}</p>
                        <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                        <div style="margin-bottom:5px">
                            <span style="padding:10px 25px; font-weight: 900; font-size: 14px;">T {{ $subTotalqty }}</span>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; color: black; border: 1px solid #000;width:100px; padding: 5px 0 ;border-radius: 5px;margin: 7px auto;">
                            @if($orderItem->operation_id)
                                {{ $services }}
                            @else
                                Operation data missing
                            @endif
                        </p>
                        @if($orderItem->Item && $orderItem->categories)
                            @if($laundryOrderItem && $orderItem->Item->name == 'Laundry By Weight')
                                <p style="font-weight: bold; font-size: 12px; color: black;">{{ $laundryOrderItem->Item->name }}/{{ $laundryOrderItem->categories->name }}</p>
                                {{-- <p style="font-weight: bold; font-size: 12px; color: black;">{{ $laundryOrderItem->categories->name }}</p> --}}
                            @else
                                <p style="font-weight: bold; font-size: 12px; color: black;">{{ $orderItem->Item->name }}</p>
                            @endif
                        @else
                            Product or Category data missing
                        @endif
                    </div>
                </div>
            @endfor
        @endif
    @endforeach
</body>
</html>
