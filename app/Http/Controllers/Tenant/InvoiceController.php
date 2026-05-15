<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Illuminate\Support\Facades\{ // Grouped imports for facades
    Session,
    DB,
    Log,
    Validator,
    Auth
};
use Carbon\Carbon; // Date and time manipulation

class InvoiceController extends Controller
{
    public function index()
    {
        try {

            $orders = Order::select('orders.id','orders.order_number','orders.total_price', 'payment_details.total_amount', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty', 'invoices.invoice_number as invoice_number')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('invoices', 'invoices.order_id', '=', 'orders.id')
                ->join('payment_details', 'payment_details.order_id', '=', 'orders.id')
                ->where('orders.is_deleted', 0)
                ->orderBy('invoices.id','desc')
                ->where('orders.status', 'delivered')
                ->paginate(10);
            // Calculate total taxable amount and total amount
            $totalTaxableAmount = $orders->sum(function ($order) {
                return $order->total_price - ($order->total_price * 0.18);
            });

            $totalAmount = $orders->sum('total_price');

            return view('admin.invoice', [
                'orders' => $orders,
                'totalTaxableAmount' => $totalTaxableAmount,
                'totalAmount' => $totalAmount,
            ]);
        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine()
            ], 500);
        }
    }

    public function indexfilter(Request $request)
    {
        try {

            $ordersQuery = Order::select('orders.id', 'invoices.invoice_number', 'orders.order_number','orders.total_price', 'payment_details.total_amount', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('invoices', 'invoices.order_id', '=', 'orders.id')
                ->join('payment_details', 'payment_details.order_id', '=', 'orders.id')
                ->where('orders.is_deleted', 0)
                ->where('orders.status', 'delivered');

            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $endDate = Carbon::parse($endDate)->endOfDay();
                $ordersQuery->whereBetween('orders.updated_at', [$startDate, $endDate]);
            }

            $orders = $ordersQuery->orderBy('orders.created_at', 'desc')->get();

            $totalTaxableAmount = $orders->sum(function ($order) {
                return  $order->total_price / 1.18;
            });

            $totalAmount = $orders->sum('total_price');

            if ($request->ajax()) {
                return response()->json([
                    'orders' => $orders,
                    'totalTaxableAmount' => $totalTaxableAmount,
                    'totalAmount' => $totalAmount
                ]);
            }

            return view('admin.invoice', [
                'orders' => $orders,
                'totalTaxableAmount' => $totalTaxableAmount,
                'totalAmount' => $totalAmount,
                'startDate' => $request->input('startDate'),
                'endDate' => $request->input('endDate')
            ]);
        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine()
            ], 500);
        }
    }
    public function export(Request $request)
    {
        $dateRange = $request->input('date_range');

        if ($dateRange) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();

            $orders = Order::select('orders.id', 'invoices.invoice_number', 'orders.order_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty','orders.updated_at')
                ->with('paymentDetail')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('invoices', 'invoices.order_id', '=', 'orders.id')
                ->whereBetween('orders.updated_at', [$startDate, $endDate])
                ->where('orders.is_deleted', 0)
                ->where('orders.status', 'delivered')
                ->whereNotNull('orders.updated_at') // Ensure updated_at is not null
                ->orderBy('invoices.invoice_number', 'asc')
                ->get();
        } else {
            $orders = Order::select('orders.id', 'invoices.invoice_number', 'orders.order_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty','orders.updated_at')
                ->with('paymentDetail')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('invoices', 'invoices.order_id', '=', 'orders.id')
                ->where('orders.is_deleted', 0)
                ->where('orders.status', 'delivered')
                ->whereNotNull('orders.updated_at') // Ensure updated_at is not null
                ->orderBy('invoices.invoice_number', 'asc')
                ->get();
        }
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'No orders found for the selected date range.');
        }

        return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }


    public function analitices()
    {
        // Assuming a tax rate of 10%
        $taxRate = 0.18;

        // Retrieve order statistics
        $ordersDataCount = Order::select([
            DB::raw('COUNT(*) as totalOrders'), // Total number of orders
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pendingOrders'), // Total pending orders
            DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as deliveredOrders'), // Total delivered orders
            DB::raw('SUM(total_price) as totalOrdersAmount') // Total amount of all orders (Taxable amount)
        ])
            ->where('is_deleted', '!=', 1) // Exclude deleted orders
            ->first();

        // Calculate the tax and gross total
        $taxableAmount = $ordersDataCount->totalOrdersAmount;
        $taxAmount = $taxableAmount * $taxRate;
        $grossTotal = $taxableAmount - $taxAmount;

        // Retrieve detailed orders data along with customer names
        $totalOrderByCustomers = Order::select([
            'orders.invoice_number', // Invoice number of the order
            'orders.order_number', // Order number
            'orders.user_id', // ID of the user who placed the order
            'orders.order_date', // Date of the order
            'orders.status', // Current status of the order
            'orders.total_price', // Total price of the order
            'users.name', // Name of the user who placed the order
            'payment_details.total_amount'
        ])
            ->where('orders.is_deleted', '!=', 1) // Exclude deleted orders
            ->join('users', 'orders.user_id', '=', 'users.id') // Join with users table to get user details
            ->join('payment_details', 'payment_details.order_id', '=', 'orders.id')
            ->orderBy('orders.order_date', 'desc') // Order by date in descending order
            ->get();

        // Return the view with the retrieved data
        return view('admin.detail', [
            'totalOrders' => $ordersDataCount->totalOrders, // Pass total orders count to the view
            'pendingOrders' => $ordersDataCount->pendingOrders, // Pass pending orders count to the view
            'deliveredOrders' => $ordersDataCount->deliveredOrders, // Pass delivered orders count to the view
            'totalOrdersAmount' => $ordersDataCount->totalOrdersAmount, // Pass total orders amount (Taxable amount) to the view
            'taxAmount' => $taxableAmount, // Pass the calculated tax amount to the view
            'grossTotal' => $grossTotal, // Pass the calculated gross total to the view
            'taxableAmount' => $taxAmount, // Pass the calculated gross total to the view
            //'totalOrderByCustomers' => $totalOrderByCustomers, // Pass detailed order data to the view
        ]);
    }


    public function filterData(Request $request)
    {
        // Retrieve request inputs
        $search = $request->input('search');
        $month = $request->input('month');
        $date = $request->input('date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $taxRate = 1.18; // Assuming 18% tax rate

        // Build query for filtering orders
        $query = Order::select(
            'orders.order_number',
            'orders.invoice_number',
            'orders.order_date',
            'orders.status',
            'orders.total_price',
            'orders.is_deleted',
            'orders.id',
            'users.name',
            'payment_details_1.total_amount' // Alias for the first payment_details table
        )
        ->where('orders.is_deleted', '!=', 1)
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->join('payment_details as payment_details_1', 'payment_details_1.order_id', '=', 'orders.id'); // Alias for the first payment_details table

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('orders.order_number', 'LIKE', "%{$search}%")
                ->orWhere('users.name', 'LIKE', "%{$search}%")
                ->orWhere('orders.invoice_number', 'LIKE', "%{$search}%");
            });
        }

        // Apply month filter
        if (!empty($month)) {
            $query->whereMonth('orders.order_date', $month);
        }

        // Apply date filter
        if (!empty($date)) {
            $query->whereDate('orders.order_date', $date);
        }

        // Apply date range filter (start date and end date)
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('orders.order_date', [$startDate, $endDate]);
        }

        // Clone the query to avoid overwriting it when applying different conditions
        $countQuery = clone $query;

        // Get filtered order statistics
        $totalOrders = $countQuery->count();

        // Clone the base query to get pending orders
        $pendingOrders = clone $query;
        $pendingOrdersCount = $pendingOrders->where('orders.status', 'pending')->count();

        // Clone the base query to get delivered orders
        $deliveredOrders = clone $query;
        $deliveredOrdersCount = $deliveredOrders->where('orders.status', 'delivered')->count();

        // Calculate total orders amount (sum of all prices)
        $totalOrdersAmountQuery = clone $query;
        $totalOrdersAmount = $totalOrdersAmountQuery->sum('payment_details_1.total_amount'); // Use alias for total_amount

        // Calculate tax and taxable amount
        $taxAmount = $totalOrdersAmount / $taxRate;
        $grossTotal = $totalOrdersAmount - $taxAmount;

        // Now get detailed order data with the original query
        $orders = $query->orderBy('orders.order_date', 'desc')->get();

        // Return JSON response
        return response()->json([
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrdersCount,
            'deliveredOrders' => $deliveredOrdersCount,
            'totalOrdersAmount' => $totalOrdersAmount,
            'taxAmount' => $grossTotal,
            'grossTotal' => $taxAmount,
            'orders' => $orders,
        ]);
    }


}
