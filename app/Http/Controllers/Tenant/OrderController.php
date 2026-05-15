<?php

namespace App\Http\Controllers\Tenant;

// Importing necessary classes and models
use App\Http\Controllers\Controller; // Base controller
use App\Models\{ // Grouped imports for models
    Order,
    User,
    ProductItem,
    ProductCategory,
    PaymentDetail,
    Discount,
    OrderItem,
    Service,
    Tenant,
    Operations,
    Item,
    ItemDetail,
    Services,
    Category,
    LundaryOrderItem,
    Invoice
};



// Importing necessary services and facades
use Illuminate\Http\Request; // Handling HTTP requests
use Barryvdh\DomPDF\Facade\Pdf; // PDF generation using DomPDF
use App\Services\WhatsAppService; // Custom WhatsApp service
use Illuminate\Support\Facades\{ // Grouped imports for facades
    Session,
    DB,
    Log,
    Validator,
    Auth
};
use Carbon\Carbon; // Date and time manipulation
use Throwable; // Exception handling
use Exception;
use App\Services\SmsService; // Custom SMS service


class OrderController extends Controller
{

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Generates a random string of the specified length.
     *
     * @param int $length Length of the random string to generate. Default is 6.
     * @return string
     */
    private function generateRandomString($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generates an array of time slots for appointment scheduling.
     *
     * @return array
     */
    private function generateTimeSlots()
    {
        $times = [];
        $timesingle = [];
        $hours = range(9, 19); // Hours from 9 AM to 8 PM

        foreach ($hours as $hour) {
            // Format start time
            if ($hour < 12) {
                $start_time = sprintf('%d:00 AM', $hour);
            } elseif ($hour == 12) {
                $start_time = '12:00 PM';
            } else {
                $start_time = sprintf('%d:00 PM', $hour - 12);
            }

            // Calculate next hour for end time
            $next_hour = $hour + 1;
            if ($next_hour < 12) {
                $end_time = sprintf('%d:00 AM', $next_hour);
            } elseif ($next_hour == 12) {
                $end_time = '12:00 PM';
            } else {
                $end_time = sprintf('%d:00 PM', $next_hour - 12);
            }

            // Add to the times array
            $times[] = [
                'start' => $start_time,
                'end' => $end_time,
                'range' => sprintf('%s - %s', $start_time, $end_time)
            ];

            // Add to the timesingle array
            $timesingle[] = $start_time;
        }

        return [
            'time_ranges' => $times,
            'single_times' => $timesingle
        ];
    }



    /**
     * Sends an SMS notification using the provided payload.
     *
     * @param string $payload JSON-encoded payload for the SMS API
     * @return void
     */
    private function sendSmsNotification($payload)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'authkey: 426794Akjeezy8u669e32f2P1',
                'content-type: application/json',
                'Cookie: PHPSESSID=kgm8ohaofmr3v04i9gruu0kjs6'
            ],
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('SMS sending failed: ' . curl_error($curl));
        } else {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            Log::info("SMS sent successfully. HTTP Status Code: $http_code. Response: $response");
        }

        curl_close($curl);
    }

    /**
     * Handles adding a new order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addOrder(Request $request)
    {
        //try {
            // Step 1: Validate and retrieve the incoming request data
            $validatedData = $request->validate([
                'client_num' => 'required|numeric',
                'client_name' => 'required|min:2|max:20',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required',
                'discount' => 'required|numeric',
                'total_qty' => 'required|integer|min:1',
                'itemname' => 'required|array|min:1',
                'itemname.*' => 'required|string',
                'itemcategory' => 'required|array|min:1',
                'itemcategory.*' => 'required|string',
                'itemqty' => 'required|array|min:1',
                'itemqty.*' => 'required|integer|min:1',
                'itemprice' => 'required|array|min:1',
                'itemprice.*' => 'required|numeric|min:0',
            ]);

            //dd($request->lundaryProductName);

            // Step 2: Process item names and retrieve corresponding item IDs
            $itemIds = Item::whereIn('name', $request->itemname)->pluck('id', 'name')->toArray();
            $categoryIds = Category::whereIn('name', $request->itemcategory)->pluck('id', 'name')->toArray();

             // Retrieve or create client
             $client = User::where('mobile', $validatedData['client_num'])->first();

            // Check if client exists and is marked as deleted
            if ($client) {
                if ($client->is_deleted == 1) {
                    // Update the client to set is_deleted to 0
                    $client->update(['name' => $validatedData['client_name'] , 'is_deleted' => 0]);
                }
                $user_id = $client->id;
            } else {
                // Create a new user if the client doesn't exist
                $user_id = User::create([
                    'name' => $validatedData['client_name'],
                    'mobile' => $validatedData['client_num'],
                    'role_id' => 2,
                ])->id;
            }


            // Step 3: Validate item IDs and categories (handle missing item names)
            $orderItems = [];
            foreach ($request->itemname as $key => $itemvalue) {
                // Check if the item ID and category exist, otherwise skip or throw an error
                if (!isset($itemIds[$itemvalue]) || !isset($categoryIds[$request->itemcategory[$key]])) {
                    return back()->withErrors("Item or category not found: {$itemvalue} / {$request->itemcategory[$key]}");
                }

                $orderItems[] = [
                    'item_name' => $itemvalue,
                    'item_id' => $itemIds[$itemvalue],
                    'category_id' => $categoryIds[$request->itemcategory[$key]],
                    'service_id' => $request->itemservice[$key] ?? null,
                    'item_qty' => $request->itemqty[$key] ?? null,
                    'item_price' => $request->itemprice[$key] ?? null,
                    'qty_x_price' => $request->qtyxprice[$key] ?? null,
                    'unit' => $request->unit[$key] ?? null,
                    'weight' => $request->clothlundaryqty[$key] ?? 0,
                ];

            }

            // Step 4: Proceed with order creation
            $deliveryTime24Hour = Carbon::createFromFormat('g:i A', $validatedData['delivery_time'])->format('H:i:s');

            // Retrieve or create client
            $client = User::firstOrCreate(
                ['mobile' => $validatedData['client_num']],
                ['name' => $validatedData['client_name'], 'role_id' => 2]
            );

            // Step 5: Create order
            $discountId = $this->getDiscountId($request->discount);
            list($totalPriceDis, $totalDiscount) = $this->calculateTotalPrice($request);
            $order = Order::create([
                'invoice_number' => '',
                'user_id' => $client->id,
                'order_date' => Carbon::now()->toDateString(),
                'order_time' => Carbon::now()->toTimeString(),
                'delivery_date' => $validatedData['delivery_date'],
                'delivery_time' => $deliveryTime24Hour,
                'discount_id' => $discountId,
                'service_id' => null,
                'status' => 'pending',
                'total_qty' => $validatedData['total_qty'],
                'total_price' => $request->gross_total,
            ]);

            // Generate and update order number
            if ($order) {
                $orderNumber = 'ORD-' . $this->generateRandomString();
                $order->order_number = $orderNumber;
                $order->save();
            }
            // Step 6: Insert order items
            foreach ($orderItems as $orderItemkey  => $item) {
                $orderItem = $order->orderItems()->create([
                    'order_id' => $order->id,
                    'product_item_id' => $item['item_id'],
                    'product_category_id' => $item['category_id'],
                    'operation_id' => $item['service_id'],
                    'quantity' => $item['item_qty'],
                    'operation_price' => $item['item_price'],
                    'price' => $item['qty_x_price'],
                    'unit' => $item['unit'],
                    'weight' => $item['weight'],
                    'status' => 'pending'
                ]);
                $lundaryitemId = [];
                // Handle laundry items (if applicable)
                if ($item['unit'] == 'Kg' && $request->lundaryProductName) {
                    $lundaryitemIds = []; // Initialize an empty array to store IDs
                    $lundaryProductCategroyIds = []; // Initialize an empty array to store IDs
                    $lundaryProductQtys = []; // Initialize an empty array to store IDs
                    foreach ($request->lundaryProductName as $lundarykey => $lundaryvalue) {
                        // Fetch the ID for each product name and store it in the array
                        $lundaryitem = Item::where('name', $lundaryvalue)->first(); // Get the first matching item
                        if ($item) {
                            $lundaryitemIds[] = $lundaryitem->id; // Add the item's id to the array
                        }
                    }

                    //print_r($lundaryitemIds);

                    foreach ($request->lundaryProductCategroyId as $lundaryProductCategroykey => $lundaryProductCategroyvalue) {
                        // Fetch the ID for each product name and store it in the array
                        $lundarycategory = Category::where('name', $lundaryProductCategroyvalue)->first(); // Get the first matching item
                        if ($lundarycategory) {
                            $lundaryProductCategroyIds[] = $lundarycategory->id; // Add the item's id to the array
                        }
                    }


                    foreach ($request->lundaryProductQty as $lundaryProductQtykey => $lundaryProductQtyvalue) {
                        foreach ($lundaryProductQtyvalue as $laundprokey => $laundprovalue) {
                            $lundaryProductQtys[] = $laundprovalue; // Add the item's id to the array
                        }
                    }
                    foreach ($lundaryitemIds as $lundarykey => $lundaryvalue) {
                        LundaryOrderItem::create([
                            'order_item_id' => $order->id,
                            'ProductName' => $lundaryitemIds[$lundarykey],
                            'ProductQty' => $lundaryProductQtys[$lundarykey],
                            'ProductCategroyId' => $lundaryProductCategroyIds[$lundarykey] ?? null
                        ]);
                    }
                }
            }
            // Step 7: Payment details creation
            PaymentDetail::create([
                'order_id' => $order->id,
                'total_quantity' => $validatedData['total_qty'],
                'total_amount' => $totalPriceDis,
                'discount_amount' => $totalDiscount,
                'service_charge' => $request->express_charge == '1' ? ($totalPriceDis * 50) / 100 : 0,
                'paid_amount' => 0,
                'status' => 'Due',
                'payment_type' => null
            ]);


            $clientPhoneNumber = '91' . $validatedData['client_num'];
            $message = $orderNumber . ' amounting to ' . $totalPriceDis . ' for ' . $validatedData['total_qty'] . ' Item' ;
            $payload = json_encode([
                "template_id" => "66cf2880d6fc053eab375372",
                "recipients" => [
                    [
                        "mobiles" => $clientPhoneNumber,
                        "ordernumber" => $message,
                        "name" => $validatedData['client_name'],
                    ]
                ]
            ]);

            $this->sendSmsNotification($payload);

            // Step 8: Redirect to order view
            return redirect()->route('viewOrder');

        // } catch (\Exception $e) {
        //     // Handle errors and return back with error message
        //     return back()->withErrors($e->getMessage());
        // }
    }


    /**
     * Handles updating an existing order.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrder(Request $request, $id)
    {
        //try {
            // Step 1: Validate and retrieve the incoming request data
            $validatedData = $request->validate([
                'client_num' => 'required|numeric',
                'client_name' => 'required|min:2|max:20',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required',
                'discount' => 'required|numeric',
                'total_qty' => 'required|integer|min:1',
                'itemname' => 'required|array|min:1', // Ensuring at least one item is selected
                'itemname.*' => 'required|string', // Validate each item name in the array
                'itemcategory' => 'required|array|min:1', // Ensuring at least one category is selected
                'itemcategory.*' => 'required|string', // Validate each category
                'itemqty' => 'required|array|min:1',
                'itemqty.*' => 'required|integer|min:1', // Each quantity should be an integer
                'itemprice' => 'required|array|min:1',
                'itemprice.*' => 'required|numeric|min:0', // Each price should be a numeric value
            ]);

            // Step 2: Process item names and retrieve corresponding item IDs
            $itemname = $request->itemname;
            foreach ($itemname as $itemkey => $itemvalue) {
                $itemId[] = Item::where('name', $itemvalue)->first();
            }
            $itemIds = []; // Initialize an array to store item IDs

            // Loop through the result and store IDs in $itemIds
            foreach ($itemId as $itemkey => $item) {
                $itemIds[] = $item->id; // Add the item ID to the array
            }

            // Process laundry product names if present
            $lundaryProductName = $request->lundaryProductName;
            if ($lundaryProductName) {
                $lundaryitemIds = Item::whereIn('name', $lundaryProductName)->pluck('id')->toArray();
            }

            $itemcategory = $request->itemcategory;
            $categoryIds = [];

            foreach ($itemcategory as $categorykey => $categoryvalue) {
                $category = Category::where('name', $categoryvalue)->first();

                if ($category) {
                    $categoryIds[] = $category->id; // Add the category ID to the array
                }
            }

            // Step 3: Process services and retrieve corresponding service IDs
            $itemservice = $request->itemservice;

            // Process laundry product category IDs if present
            $lundaryProductCategroyId = $request->lundaryProductCategroyId;
            if ($lundaryProductCategroyId) {
                $lundarycategoryIds = [];
                foreach ($lundaryProductCategroyId as $lundaryProductCategroykey => $lundaryProductCategroyvalue) {
                    $lundarycategory = Category::where('name', $lundaryProductCategroyvalue)->first();

                    if ($lundarycategory) {
                        $lundarycategoryIds[] = $lundarycategory->id; // Add the category ID to the array
                    }
                }
            }

            // Step 4: Prepare the order item details
            $itemqty = $request->itemqty;
            $itemprice = $request->itemprice;
            $qtyxprice = $request->qtyxprice;
            $weight = $request->clothlundaryqty ?? [];
            $unit = $request->unit;

            $lundaryProductQty = $request->lundaryProductQty;

            $order_item = []; // Initialize an empty array to store order items
            foreach ($itemname as $key => $itemvalue) {
                $order_item[] = [
                    'item_name' => $itemvalue,
                    'item_id' => $itemIds[$key] ?? null, // Corresponding item ID
                    'category_id' => $categoryIds[$key] ?? null, // Corresponding category ID
                    'service_id' => $itemservice[$key] ?? null, // Corresponding service ID
                    'item_qty' => $itemqty[$key] ?? null, // Item quantity
                    'item_price' => $itemprice[$key] ?? null, // Item price
                    'qty_x_price' => $qtyxprice[$key] ?? null, // Quantity * price
                    'unit' => $unit[$key] ?? null, // Unit
                    'weight' => $weight[$key] ?? 0 // Weight (if any)
                ];
            }

            // Prepare laundry items if present
            if ($lundaryProductCategroyId) {
                $lundary_item = []; // Initialize an empty array to store laundry items
                foreach ($lundaryProductName as $lundaryProductNamekey => $lundaryitemvalue) {
                    $lundary_item[] = [
                        'lundary_item_name' => $lundaryitemvalue,
                        'lundary_item_id' => $lundaryitemIds[$lundaryProductNamekey] ?? null, // Corresponding item ID
                        'lundary_category_id' => $lundarycategoryIds[$lundaryProductNamekey] ?? null, // Corresponding category ID
                        'lundary_item_qty' => $lundaryProductQty[$lundaryProductNamekey] ?? null, // Item quantity
                    ];
                }
            }

            // Step 5: Convert delivery time to 24-hour format
            $deliveryTime24Hour = Carbon::createFromFormat('g:i A', $validatedData['delivery_time'])->format('H:i:s');

            // Step 6: Retrieve or create the client (user)
            $client = User::firstOrCreate(
                ['mobile' => $validatedData['client_num']],
                ['name' => $validatedData['client_name'], 'role_id' => 2]
            );

            // Step 7: Get discount ID and calculate total price
            $discountId = $this->getDiscountId($request->discount);
            list($totalPriceDis, $totalDiscount) = $this->calculateTotalPrice($request);

            // Step 8: Update the order
            $order = Order::findOrFail($id);
            $order->update([
                'user_id' => $client->id,
                'order_date' => $validatedData['booking_date'],
                'order_time' => $validatedData['booking_time'],
                'delivery_date' => $validatedData['delivery_date'],
                'delivery_time' => $deliveryTime24Hour,
                'discount_id' => $discountId,
                'service_id' => null,
                'status' => 'pending',
                'total_qty' => $validatedData['total_qty'],
                'total_price' => $request->gross_total
            ]);

            // Step 9: Delete existing order items and insert new ones
            $order->orderItems()->delete(); // Remove existing items

            LundaryOrderItem::where('order_item_id', $id)->delete();


            foreach ($order_item as $item) {
                $orderItem = $order->orderItems()->create([
                    'product_item_id' => $item['item_id'],
                    'product_category_id' => $item['category_id'],
                    'operation_id' => $item['service_id'], // Assuming service ID is the operation
                    'quantity' => $item['item_qty'],
                    'operation_price' => $item['item_price'],
                    'price' => $item['qty_x_price'],
                    'unit' => $item['unit'],
                    'weight' => $item['weight'],
                    'type' => 'Some Type', // Placeholder type
                    'comment' => null, // Optional comment
                    'status' => 'pending'
                ]);

                // Handle laundry items if the unit is Kg
                if ($item['unit'] == 'Kg' && isset($lundary_item)) {
                    foreach ($lundary_item as $lundaryvalue) {
                        LundaryOrderItem::create([
                            'order_item_id' => $order->id,
                            'ProductName' => $lundaryvalue['lundary_item_id'],
                            'ProductQty' => $lundaryvalue['lundary_item_qty']['0'],
                            'ProductCategroyId' => $lundaryvalue['lundary_category_id']
                        ]);
                    }
                }
            }

            // Step 10: Update payment details for the order
            $paymentDetail = PaymentDetail::where('order_id', $order->id)->first();

            if ($paymentDetail) {
                $paymentDetail->update([
                    'total_quantity' => $validatedData['total_qty'],
                    'total_amount' => $totalPriceDis,
                    'discount_amount' => $totalDiscount,
                    'service_charge' => $request->express_charge == '1' ? ($totalPriceDis * 50) / 100 : 0,
                    'paid_amount' => $paymentDetail->paid_amount,
                    'status' => 'Due',
                    'payment_type' => $paymentDetail->payment_type
                ]);
            } else {
                // If payment detail doesn't exist, create a new one
                PaymentDetail::create([
                    'order_id' => $order->id,
                    'total_quantity' => $validatedData['total_qty'],
                    'total_amount' => $totalPriceDis,
                    'discount_amount' => $totalDiscount,
                    'service_charge' => $request->express_charge == '1' ? ($totalPriceDis * 50) / 100 : 0,
                    'paid_amount' => 0,
                    'status' => 'Due',
                    'payment_type' => null
                ]);
            }

            // Step 11: Send an SMS notification to the client
            $clientPhoneNumber = '91' . $validatedData['client_num'];
            $message = $order->order_number . ' amounting to ' . $totalPriceDis .' for '. $validatedData['total_qty'].' items ';
            $payload = json_encode([
                "template_id" => "669e3596d6fc0569d040c232",
                "recipients" => [
                    [
                        "mobiles" => $clientPhoneNumber,
                        "ordernumber" => $message,
                        "name" => $validatedData['client_name'],
                    ]
                ]
            ]);

            // Function to send SMS (uncomment when ready)
            $this->sendSmsNotification($payload);

            // Redirect to order view page
            return redirect()->route('viewOrder')->with('success', 'Order updated successfully.');
        // } catch (\Exception $e) {
        //     // Handle errors and return back with error message
        //     return back()->withErrors($e->getMessage());
        // }
    }

    /**
     * Displays the Edit Order page with relevant data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $productItems = Item::with(['itemDetails' => function($query) {
            $query->select('id', 'item_id', 'category', 'service', 'price');
        }])->get();

        $groupedProductItems = $productItems->map(function($productItem) {
            $groupedDetails = $productItem->itemDetails
                ->groupBy('category')
                ->map(function($details) {
                    return $details->groupBy('service');
                });

            return [
                'product_item' => $productItem,
                'grouped_details' => $groupedDetails,
            ];
        });

        // Retrieve all discounts and services
        $discounts = Discount::all();
        $services = Service::all();
        $timeSlots = $this->generateTimeSlots();

        $currentdatetime = Carbon::now();
        $currentdate = $currentdatetime->toDateString();
        $currenttime = $currentdatetime->toTimeString();

        return view('admin.EditOrder', compact('groupedProductItems', 'discounts', 'services', 'timeSlots', 'currentdate', 'currenttime'));
    }

    public function getOperationData($pid, $pname, $others = [])
    {
        // Retrieve operation data based on provided product ID and name
        $data = Operations::select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
            ->where([
                'pc.product_item_id' => $pid,
                'pc.name' => $pname,
            ])
            ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
            ->get();

        // Return the operation view with data and additional parameters
        return view('admin.operation.operationview', ['data' => $data, "others" => $others])->render();
    }

    public function getServiceData(Request $request)
    {

        // Retrieve parameters from request and call getOperationData
        $pId = $request->id;
        $pname = $request->name;
        $others = $request->others ?? [];
        return $this->getOperationData($pId, $pname, $others);
    }

    public function fetchClientName(Request $request)
    {
        try {
            // Validate request input
            $request->validate([
                'client_num' => 'required|numeric|digits:10',
            ]);

            // Find the user by mobile number
            $user = User::where('mobile', $request->client_num)->where('is_deleted', 0)->first();
            if ($user) {
                return response()->json([
                    'success' => true,
                    'client_name' => $user->name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found for the given mobile number.',
                ]);
            }
        } catch (\Throwable $throwable) {
            // Handle and log exception
            return response()->json('error', 'Something Went Wrong.');
        }
    }



    private function getDiscountId($discount)
    {
        // Map discount values to discount IDs
        $discountMapping = [
            '5' => 1,
            '10' => 2,
            '15' => 3,
            '20' => 4
        ];
        return $discountMapping[$discount] ?? null;
    }

    private function calculateTotalPrice(Request $request)
    {
        $grossPrice = $request->gross_total;
        $totalDiscount = ($grossPrice * ($request->discount ? $request->discount : 0)) / 100;
        $totalPrice = $grossPrice - $totalDiscount;

        if ($request->express_charge == '1') {
            $totalPrice += ($totalPrice * 50) / 100; // Add express charge
        }

        return [$totalPrice, $totalDiscount];
    }


    public function getServices(Request $request)
    {
        $item = $request->input('item');
        $type = $request->input('type');

        // Fetch the related product category
        $productCategory = ProductCategory::where('product_item_id', $item)
            ->where('id', $type)
            ->with('service')
            ->first();

        // Get the services associated with the product category
        $services = $productCategory ? $productCategory->service : [];
        // dd($services);

        return response()->json(['services' => $services]);
    }


    public function getPrice(Request $request)
    {
        $item = $request->input('item');
        $type = $request->input('type');
        $service = $request->input('service');

        // Fetch the price based on item, type, and service
        $productCategory = ProductCategory::where('product_item_id', $item)
            ->where('id', $type)
            ->where('operation_id', $service)
            ->first();

        $price = $productCategory ? $productCategory->price : null;

        return response()->json(['price' => $price]);
    }

    public function editOrder(Request $request, $id)
    {
        // Fetching the order and joining user information
        $order = Order::select("users.name", "users.mobile", "orders.*")
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->findOrFail($id);

        // Convert delivery time to 12-hour format
        $deliveryTime = Carbon::parse($order->delivery_time)->format('g:i A');

        // Fetching the order items and their related categories and services
        $orderItems = OrderItem::where('order_id', $id)
            ->join('categories', 'categories.id', '=', 'order_items.product_category_id')
            ->join('items', 'items.id', '=', 'order_items.product_item_id')
            ->select('order_items.*', 'categories.name as category_name', 'items.name as items_name')
            ->get();

        // Fetching products and grouping them by category and service
        $productItems = Item::with(['itemDetails' => function($query) {
            $query->select('id', 'item_id', 'category', 'service', 'price');
        }])->get();

        // Grouping product details by category and service for easier access on the frontend
        $groupedProductItems = $productItems->map(function($productItem) {
            $groupedDetails = $productItem->itemDetails
                ->groupBy('category')
                ->map(function($details) {
                    return $details->groupBy('service');
                });

            return [
                'product_item' => $productItem,
                'grouped_details' => $groupedDetails,
            ];
        });

        // Fetching discounts and services for dynamic dropdowns
        $discounts = Discount::all();
        $services = Service::all();
        $timeSlots = $this->generateTimeSlots(); // Assuming this method exists for generating time slots

        // Getting the current date and time for pre-filling the form
        $currentdatetime = Carbon::now();
        $currentdate = $currentdatetime->toDateString();
        $currenttime = $currentdatetime->toTimeString();

        // Fetch the discount applied to the order
        $orderDiscount = Discount::find($order->discount_id);
        if ($orderDiscount) {
            $discountAmount = $orderDiscount->amount;
            $orderDiscountamount = $order->total_price * ($orderDiscount->amount/100);
            $total_amount = $order->total_price - ($order->total_price * ($orderDiscount->amount/100));
        }else{
            $discountAmount =  0;
            $orderDiscountamount = 0;
            $total_amount = $order->total_price;
        }

        // Passing all necessary data to the view
        return view('admin.orderupdate', compact('order', 'orderItems', 'groupedProductItems', 'discounts', 'services', 'timeSlots', 'currentdate', 'currenttime','total_amount','orderDiscount','orderDiscountamount','discountAmount'));
    }


    public function getAllOperationData($pid, $pname, $others = [])
    {
        $data = Operations::select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
            ->where([
                'pc.product_item_id' => $pid,
                'pc.name' => $pname,
            ])
            ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
            ->get();

        // dd($data);
        foreach ($data as &$operationData) {
            $operationData->isMatch = false;
            if (!empty($others[$operationData->pid]) && isset($others[$operationData->pid]['Operations'])) {
                foreach ($others[$operationData->pid]['Operations'] as $operation) {
                    if ($operation['service_id'] == $operationData->op_id) {
                        $operationData->isMatch = true;
                    }
                }
            }
        }
        return view('admin.operation.editoperationview', ['data' => $data, "others" => $others])->render();
    }



    public function getAllServiceData(Request $request)
    {
        $pId = $request->id;
        $pname = $request->name;
        $others = $request->others ?? [];
        // dd($others);
        return $this->getAllOperationData($pId, $pname, $others);
    }

    public function OrderDetail(Request $request, $orderId)
    {
        try {
            // Find the order by its ID
            $orders = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

            // If no order is found, redirect back with an error message
            if (!$orders) {
                return redirect()->back()->with('error', 'Order not found.');
            }
            $subTotalAmount = '0';
            $servicesList = [];  // To store the service names
            foreach ($orders->orderItems as $orderItem) {
                // Calculate the subtotal amount
                $subTotalAmount += $orderItem->price;
            }


            // Calculate the discount amount
            $discountAmount = 0;
            if ($orders->discounts !== null) {
                $discountPercentage = $orders->discounts->amount;
                $discountAmount = ($discountPercentage / 100) * $subTotalAmount;
            }

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Return the view with the order details
            return view('admin.OrderDetail', [
                'orders' => $orders,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount
            ]);

        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            // Catch any exceptions and redirect back with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }


    public function viewOrder(Request $request)
    {
        try {
            $query = Order::with(['user', 'paymentDetail', 'orderItems'])
                ->where('orders.is_deleted', '!=', 1);

            // Apply search filters if provided
            if ($request->ajax()) {
                $search = $request->input('search');
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('order_number', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('mobile', 'like', '%' . $search . '%');
                            });
                    });
                }


                $orders = $query->orderBy('orders.id', 'desc')->paginate(10);

                // Map additional data to the orders
                $orders->each(function ($order) {
                    $order->payment_status = $order->paymentDetail ? $order->paymentDetail->status : null;
                    $order->name = $order->user ? $order->user->name : null;
                    $order->mobile = $order->user ? $order->user->mobile : null;
                    $order->item_status = $order->orderItems->max('status');
                });


                return response()->json([
                    'orders' => $orders->items(),
                    'pagination' => (string) $orders->links()
                ]);
            }

            $orders = $query->orderBy('orders.id', 'desc')->paginate(10);
            $orders->each(function ($order) {
                $order->payment_status = $order->paymentDetail ? $order->paymentDetail->status : null;
                $order->name = $order->user ? $order->user->name : null;
                $order->mobile = $order->user ? $order->user->mobile : null;
                $order->item_status = $order->orderItems->max('status');
            });

            return view('admin.viewOrder', ['orders' => $orders]);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
        }
    }




    public function deleteOrder($id)
    {
        try {
            Order::where('id', '=', $id)->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function sendWhMessage(Request $request, WhatsAppService $whatsAppService, $orderId)
    {
        try {
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId); // Assuming 'Order' is your Eloquent model

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            $user = $order->user;
            $name = $user->name;
            $tracking_number = $order->invoice_number;
            $delivery_date = $order->delivery_date;
            $order_id = $order->id;

            // Generate the PDF from the 'admin.pdf' view
            $pdf = PDF::loadView('admin.pdf', compact('order', 'subTotalAmount', 'discountAmount', 'totalAmount', 'discountPercentage'));

            // Define the path to save the PDF
            $pdfPath = public_path("invoices/invoice-{$order_id}.receipt.pdf");

            // Save the PDF to the specified path
            $pdf->save($pdfPath);

            // Create a URL for the PDF file
            $pdfUrl = "https://dryclean.microlent.com//public/invoices/invoice-4.receipt.pdf";

            // Send the WhatsApp message with the PDF URL
            $response = $whatsAppService->sendMessage($name, $tracking_number, $delivery_date, $pdfUrl);

            // Delete the PDF file after sending the message
            if ($response) {
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }

            return back()->with('success', 'Order placed successfully and WhatsApp message sent.');
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return back()->with('error', $throwable->getMessage());
        }
    }


    //for download locally
    public function downloadReceipt(Request $request, $orderId)
    {
        try {

             // Find the order by its ID
             $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

             // If no order is found, redirect back with an error message
             if (!$order) {
                 return redirect()->back()->with('error', 'Order not found.');
             }

             $subTotalAmount = 0;

             foreach ($order->orderItems as $orderItem) {
                 // Calculate the subtotal amount
                 $subTotalAmount += $orderItem->price;
             }

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.pdf', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ]);

            return $pdf->download("invoice-{$order->id}.receipt.pdf");
        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function downloadInvoice(Request $request, $orderId)
    {
        try {
            // Find the order by its ID
            $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            $invoice = Invoice::where('order_id',$orderId)->first();


            $invoiceNumber = $invoice->invoice_number;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.invoiceDetail', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage, // Include discountPercentage in the view data
                'invoiceNumber' => $invoiceNumber,
            ]);

            return $pdf->download("invoice-{$order->id}.invoice.pdf");
        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }


    public function PrintReceipt(Request $request, $orderId)
    {
        try {
            // Find the order by its ID
            $order = Order::with('orderItems.categories' ,'orderItems.services','orderItems.Item', 'discounts','user')->find($orderId);



            // If no order is found, redirect back with an error message
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            // Calculate the subtotal amount
            $subTotalAmount = 0;

            foreach ($order->orderItems as $orderItem) {
                // Calculate the subtotal amount
                $subTotalAmount += $orderItem->price;
            }

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;
            //dd($order->toArray());
            // Pass data to the view
            return view('admin.receipt', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage
            ]);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function RecieptPrint(Request $request, $orderId)
    {
        try {
             // Find the order by its ID
             $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

             // If no order is found, redirect back with an error message
             if (!$order) {
                 return redirect()->back()->with('error', 'Order not found.');
             }

             $subTotalAmount = 0;

             foreach ($order->orderItems as $orderItem) {
                 // Calculate the subtotal amount
                 $subTotalAmount += $orderItem->price;
             }

             // Calculate the discount amount
             $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
             $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

             // Calculate the total amount
             $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.pdf', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ]);
            return $pdf->stream("invoice-{$order->id}.receipt.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function PrintInvoice(Request $request, $orderId)
    {
        try {
            // Find the order by its ID
            $order = Order::select('orders.*','invoices.invoice_number')->with('orderItems.categories','orderItems.Item', 'discounts','user')->join('invoices', 'invoices.order_id', '=', 'orders.id')->find($orderId);

            // If no order is found, redirect back with an error message
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            // Calculate the subtotal amount
            // $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
            //     return $orderItem->quantity * $orderItem->operation_price;
            // });

            $subTotalAmount = 0;

            foreach ($order->orderItems as $orderItem) {
                // Calculate the subtotal amount
                $subTotalAmount += $orderItem->price;
            }

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            $invoice = Invoice::where('order_id',$orderId)->first();


            $invoiceNumber = $invoice->invoice_number;
            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;
            //dd($order->toArray());
            // Pass data to the view
            return view('admin.invoicePdf', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage,
                'invoiceNumber' => $invoiceNumber,
            ]);
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function InvoicePrint(Request $request, $orderId)
    {
        try {
            // Fetch the latest order with related order items, user, and discounts
             // Find the order by its ID
             $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;
            $invoice = Invoice::where('order_id',$orderId)->first();


            $invoiceNumber = $invoice->invoice_number;

            // Pass data to the view
            $pdf = PDF::loadView('admin.invoiceDetail', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage, // Include discountPercentage in the view data
                'invoiceNumber' => $invoiceNumber,
            ]);

            return $pdf->stream("invoice-{$order->id}.invoice.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function tagList(Request $request, $orderId)
    {
        try {
            // Find the order by its ID
            $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

            // If no order is found, redirect back with an error message
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }
            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            $subTotalqty = 0;

            foreach ($order->orderItems as $orderItem) {
                // Calculate the subtotal amount
                $subTotalqty += $orderItem->quantity;
            }


            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            $laundryOrderItem = LundaryOrderItem::with('categories','Item')->where('order_item_id', $orderId)->first();

            // dd($laundryOrderItem);

            // Pass data to the view
            return view('admin.tagslist', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'subTotalqty' => $subTotalqty,
                'laundryOrderItem' => $laundryOrderItem,
            ]);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function printTaglist(Request $request, $orderId)
    {
        try {
             // Find the order by its ID
             $order = Order::with('orderItems.categories','orderItems.Item', 'discounts','user')->find($orderId);

             // If no order is found, redirect back with an error message
             if (!$order) {
                 return redirect()->back()->with('error', 'Order not found.');
             }


            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            $subTotalqty = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity;
            });

            $discountPercentage = $order->discounts->amount ?? 0;
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;
            $totalAmount = $subTotalAmount - $discountAmount;

            $laundryOrderItem = LundaryOrderItem::with('categories','Item')->where('order_item_id', $orderId)->first();

            // Define the custom paper size (144pt x 187pt)
            $customPaper = [0, 0, 144, 187];

            $pdf = PDF::loadView('admin.downloadTagslist', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage,
                'subTotalqty' => $subTotalqty,
                'laundryOrderItem' => $laundryOrderItem,
            ])->setPaper($customPaper, 'portrait')->setOptions(['debug' => true]);

            return $pdf->stream("taglist-{$order->id}.pdf");
        } catch (\Throwable $throwable) {
            dd($throwable);
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
}
