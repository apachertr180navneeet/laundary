<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

use Throwable;
use Illuminate\Support\Facades\DB;
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

            return view('erp.invoice', [
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

            return view('erp.invoice', [
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
}


