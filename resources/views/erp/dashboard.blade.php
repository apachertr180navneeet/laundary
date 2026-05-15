@extends('erp.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (session('success'))
        <div class="alert alert-premium alert-success animate-fade-in">{{ session('success') }}</div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Analytics Dashboard</h4>
            <p class="mb-0 text-muted">Welcome back, {{ Auth::user()->name ?? 'Admin' }}! Here's your business overview.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-label-primary p-2 px-3">
                <i class="ti ti-calendar me-1"></i> {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    {{-- Stat Cards Row --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-in">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stat-icon primary"><i class="ti ti-users"></i></div>
                    <span class="stat-trend text-primary"><i class="ti ti-trending-up"></i> +{{ $newClientsThisMonth ?? 0 }} this month</span>
                </div>
                <div class="stat-value">{{ $clientCounts ?? 0 }}</div>
                <div class="stat-label">Total Clients</div>
                <div class="progress progress-premium mt-3">
                    <div class="progress-bar" style="width: 78%"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-in-delay-1">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stat-icon success"><i class="ti ti-package"></i></div>
                    <span class="stat-trend text-success"><i class="ti ti-trending-up"></i> {{ $todayOrderCounts ?? 0 }} today</span>
                </div>
                <div class="stat-value">{{ $orderCounts ?? 0 }}</div>
                <div class="stat-label">Total Orders</div>
                <div class="progress progress-premium mt-3">
                    <div class="progress-bar" style="width: {{ $orderCounts > 0 ? min(($todayOrderCounts/$orderCounts)*100, 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-in-delay-2">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stat-icon warning"><i class="ti ti-clock"></i></div>
                    <span class="stat-trend text-warning"><i class="ti ti-trending-down"></i> {{ $pendingOrderCounts }} pending</span>
                </div>
                <div class="stat-value">{{ $pendingOrderCounts ?? 0 }}</div>
                <div class="stat-label">Pending Orders</div>
                <div class="progress progress-premium mt-3">
                    <div class="progress-bar bg-warning" style="width: {{ $orderCounts > 0 ? ($pendingOrderCounts/$orderCounts)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-in-delay-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stat-icon info"><i class="ti ti-currency-dollar"></i></div>
                    <span class="stat-trend text-info"><i class="ti ti-trending-up"></i> ₹{{ number_format($monthlyRevenue ?? 0) }} this month</span>
                </div>
                <div class="stat-value">₹{{ number_format($revenue ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="progress progress-premium mt-3">
                    <div class="progress-bar bg-info" style="width: {{ $revenue > 0 ? min(($monthlyRevenue/$revenue)*100, 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts & Status Row --}}
    <div class="row g-4 mb-4">
        {{-- Monthly Revenue Chart --}}
        <div class="col-12 col-lg-7 animate-fade-in">
            <div class="card card-premium h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Monthly Overview</h5>
                        <small class="text-muted">Revenue & Orders (Last 6 Months)</small>
                    </div>
                    <span class="badge bg-label-primary p-2 px-3">
                        <i class="ti ti-currency-rupee me-1"></i> ₹{{ number_format($monthlyRevenue ?? 0) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between" style="height: 200px; gap: 8px;">
                        @foreach($monthlyData as $data)
                        <div class="d-flex flex-column align-items-center flex-fill">
                            <span class="fw-semibold mb-1" style="font-size:.75rem;color:var(--pre-primary);">₹{{ number_format($data['revenue']) }}</span>
                            <div class="w-100 rounded-top" style="height: {{ $data['revenue'] > 0 ? min(($data['revenue']/max(array_column($monthlyData,'revenue')))*160, 160) : 4 }}px; background: linear-gradient(180deg, var(--pre-primary) 0%, var(--pre-accent) 100%); transition: var(--pre-transition); min-height: 4px;"></div>
                            <span class="text-muted mt-2" style="font-size:.8rem;">{{ $data['month'] }}</span>
                            <small class="fw-semibold" style="font-size:.7rem;color:var(--pre-success);">{{ $data['orders'] }} orders</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="col-12 col-lg-5 animate-fade-in-delay-1">
            <div class="card card-premium h-100">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                    <small class="text-muted">Current distribution</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat-icon success me-3" style="width:44px;height:44px;font-size:1.2rem;"><i class="ti ti-circle-check"></i></div>
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Delivered</span>
                                <span class="fw-bold">{{ $deliveredCounts ?? 0 }}</span>
                            </div>
                            <div class="progress progress-premium">
                                <div class="progress-bar bg-success" style="width: {{ $orderCounts > 0 ? ($deliveredCounts/$orderCounts)*100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat-icon warning me-3" style="width:44px;height:44px;font-size:1.2rem;"><i class="ti ti-clock"></i></div>
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Pending</span>
                                <span class="fw-bold">{{ $pendingOrderCounts ?? 0 }}</span>
                            </div>
                            <div class="progress progress-premium">
                                <div class="progress-bar bg-warning" style="width: {{ $orderCounts > 0 ? ($pendingOrderCounts/$orderCounts)*100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="stat-icon info me-3" style="width:44px;height:44px;font-size:1.2rem;"><i class="ti ti-refresh"></i></div>
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Processing</span>
                                <span class="fw-bold">{{ $processingCounts ?? 0 }}</span>
                            </div>
                            <div class="progress progress-premium">
                                <div class="progress-bar bg-info" style="width: {{ $orderCounts > 0 ? ($processingCounts/$orderCounts)*100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="row g-4 mb-4">
        {{-- Recent Orders --}}
        <div class="col-12 col-lg-7 animate-fade-in">
            <div class="card card-premium h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Today's Orders</h5>
                        <small class="text-muted">{{ count($orders) }} orders today</small>
                    </div>
                    <a href="{{ route('viewOrder') }}" class="btn btn-premium-sm btn-premium">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(isset($orders) && count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="fw-semibold">#{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ $order->total_qty ?? 0 }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status) {
                                                    'delivered' => 'success',
                                                    'processing' => 'info',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    default => 'primary'
                                                };
                                            @endphp
                                            <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($order->status ?? 'Pending') }}</span>
                                        </td>
                                        <td class="fw-semibold text-end">₹{{ number_format($order->paymentDetail->total_amount ?? $order->total_price ?? 0, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-package-off" style="font-size:3rem;color:#dee2e6;"></i>
                            <p class="text-muted mt-2 mb-0">No orders today yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="col-12 col-lg-5 animate-fade-in-delay-1">
            <div class="card card-premium h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Pending Orders</h5>
                        <small class="text-muted">Awaiting processing</small>
                    </div>
                    <span class="badge bg-label-warning p-2">{{ $pendingOrderCounts ?? 0 }}</span>
                </div>
                <div class="card-body p-0">
                    @if(isset($pendingOrders) && count($pendingOrders) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pendingOrders as $order)
                            <div class="list-group-item list-group-item-action d-flex align-items-center px-4 py-3 border-bottom">
                                <div class="me-3">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-warning fw-semibold">#{{ $order->id }}</span>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $order->created_at->diffForHumans() }} &middot; {{ $order->total_qty ?? 0 }} items</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold" style="font-size:.9rem;">₹{{ number_format($order->paymentDetail->total_amount ?? $order->total_price ?? 0, 2) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-circle-check" style="font-size:3rem;color:var(--pre-success);"></i>
                            <p class="text-muted mt-2 mb-0">No pending orders</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent border-top text-center py-3">
                    <a href="{{ route('viewOrder') }}" class="text-primary fw-semibold text-decoration-none">View All Orders <i class="ti ti-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4">
        <div class="col-12 animate-fade-in">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-sm-3 col-lg">
                            <a href="{{ route('addOrder') }}" class="btn btn-outline-premium w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="ti ti-plus-circle" style="font-size:1.5rem;"></i>
                                <span>New Order</span>
                            </a>
                        </div>
                        <div class="col-6 col-sm-3 col-lg">
                            <a href="{{ route('clientpage') }}" class="btn btn-outline-premium w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="ti ti-user-plus" style="font-size:1.5rem;"></i>
                                <span>Add Client</span>
                            </a>
                        </div>
                        <div class="col-6 col-sm-3 col-lg">
                            <a href="{{ route('invoice') }}" class="btn btn-outline-premium w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="ti ti-receipt" style="font-size:1.5rem;"></i>
                                <span>Invoices</span>
                            </a>
                        </div>
                        <div class="col-6 col-sm-3 col-lg">
                            <a href="{{ route('payment') }}" class="btn btn-outline-premium w-100 py-3 d-flex flex-column align-items-center gap-1">
                                <i class="ti ti-credit-card" style="font-size:1.5rem;"></i>
                                <span>Payments</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

