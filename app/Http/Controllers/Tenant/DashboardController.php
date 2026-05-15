<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Define the 'today' date
        $today = Carbon::today();

        // Get counts of clients and orders
        $clientCounts = User::where('is_deleted', 0)
            ->where('role_id', 2)
            ->count();

        $orderCounts = Order::where('is_deleted', 0)
            ->count();

        // Count today's orders
        $todayOrderCounts = Order::where('is_deleted', 0)
            ->where('created_at', '>=', $today)
            ->count();

        // Use eager loading instead of joins
        $orders = Order::with(['user', 'paymentDetail', 'orderItems'])
            ->where('is_deleted', 0)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Define the 10 days ago date
        $tenDaysAgo = Carbon::now()->subDays(10);

        // Get the count of pending orders in the last 10 days
        $pendingOrderCounts = Order::where('is_deleted', 0)
            ->where('status', 'pending')
            ->where('created_at', '>=', $tenDaysAgo)
            ->count();

        // Get pending orders from the last 10 days
        $pendingOrders = Order::with(['user', 'paymentDetail', 'orderItems'])
            ->where('is_deleted', 0)
            ->where('status', 'pending')
            ->where('created_at', '>=', $tenDaysAgo)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Pass data to the view
        return view('app/dashboard', compact('orders', 'clientCounts', 'orderCounts', 'todayOrderCounts', 'pendingOrderCounts', 'pendingOrders'));
    }

}
