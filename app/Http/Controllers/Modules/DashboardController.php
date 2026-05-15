<?php

namespace App\Http\Controllers\Modules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\Order;
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
        try {
            $today = Carbon::today();
        $tenDaysAgo = Carbon::now()->subDays(10);
        $startOfMonth = Carbon::now()->startOfMonth();

        // Client stats
        $clientCounts = User::where('is_deleted', 0)->where('role_id', 2)->count();
        $newClientsThisMonth = User::where('is_deleted', 0)->where('role_id', 2)
            ->where('created_at', '>=', $startOfMonth)->count();

        // Order stats
        $orderCounts = Order::where('is_deleted', 0)->count();
        $todayOrderCounts = Order::where('is_deleted', 0)
            ->where('created_at', '>=', $today)->count();

        $pendingOrderCounts = Order::where('is_deleted', 0)
            ->where('status', 'pending')
            ->where('created_at', '>=', $tenDaysAgo)
            ->count();

        $deliveredCounts = Order::where('is_deleted', 0)
            ->where('status', 'delivered')
            ->where('created_at', '>=', $tenDaysAgo)
            ->count();

        $processingCounts = Order::where('is_deleted', 0)
            ->where('status', 'processing')
            ->where('created_at', '>=', $tenDaysAgo)
            ->count();

        // Revenue
        $revenue = PaymentDetail::whereHas('order', fn($q) => $q->where('is_deleted', 0))
            ->sum('paid_amount') ?? 0;

        $monthlyRevenue = PaymentDetail::whereHas('order', fn($q) => $q->where('is_deleted', 0))
            ->where('created_at', '>=', $startOfMonth)
            ->sum('paid_amount') ?? 0;

        // Recent orders (today)
        $orders = Order::with(['user', 'paymentDetail'])
            ->where('is_deleted', 0)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Pending orders list
        $pendingOrders = Order::with(['user', 'paymentDetail'])
            ->where('is_deleted', 0)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Monthly revenue data for chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueMonth = PaymentDetail::whereHas('order', fn($q) => $q->where('is_deleted', 0))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('paid_amount') ?? 0;
            $monthlyData[] = [
                'month' => $month->format('M'),
                'revenue' => $revenueMonth,
                'orders' => Order::where('is_deleted', 0)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('erp.dashboard', compact(
            'orders', 'clientCounts', 'orderCounts', 'todayOrderCounts',
            'pendingOrderCounts', 'pendingOrders', 'revenue', 'monthlyRevenue',
            'newClientsThisMonth', 'deliveredCounts', 'processingCounts', 'monthlyData'
        ));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]); return redirect()->back()->with('error', $e->getMessage());
        }
    }
}



