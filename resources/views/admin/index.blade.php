@extends('admin.layouts')
@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="container-xxl flex-grow-1 container-p-y">

                    <!-- SaaS Hero Banner -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card saas-hero-card text-white p-4 p-md-5">
                                <div class="saas-hero-bg-accent" style="top: -200px; right: -150px;"></div>
                                <div class="saas-hero-bg-accent" style="bottom: -220px; left: -100px;"></div>

                                <div class="row align-items-center position-relative" style="z-index: 2;">
                                    <div class="col-lg-8 text-center text-lg-start">
                                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
                                            style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); font-size: 0.8rem;">
                                            <span class="saas-pulse-dot" style="background-color: #4ade80;"></span>
                                            <span>Uniform Store System • {{ now()->format('l, d M Y') }}</span>
                                        </div>
                                        <h2 class="text-white fw-bold mb-2" style="letter-spacing: -0.02em;">
                                            Welcome back, {{ Auth::user()->name }}! 👋
                                        </h2>
                                        <p class="mb-4 text-white-50 fs-6" style="max-width: 580px;">
                                            Store operations are active. You currently have
                                            <span class="badge bg-warning text-dark px-2 py-1 fw-bold">{{ $ordersByStatus['pending'] }} pending orders</span>
                                            awaiting verification and dispatch.
                                        </p>
                                        <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                                            <a href="{{ route('orders') }}" class="btn btn-light d-flex align-items-center gap-2">
                                                <i class="bx bx-shopping-bag"></i>
                                                <span>Manage Orders</span>
                                            </a>
                                            <a href="{{ route('products') }}" class="btn btn-outline-light d-flex align-items-center gap-2">
                                                <i class="bx bx-plus-circle"></i>
                                                <span>Add Product</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-center d-none d-lg-block">
                                        <i class="bx bx-store-alt" style="font-size: 8.5rem; opacity: 0.15; transform: rotate(5deg);"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visitor Analytics -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="saas-section-title mb-0">
                            <i class="bx bx-line-chart text-primary fs-5"></i>
                            <span>Traffic & Visitor Analytics</span>
                        </div>
                        <span class="badge bg-label-secondary" style="font-size: 0.72rem;">Live Metrics</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Today's Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="saas-kpi-card h-100">
                                <div class="saas-kpi-header">
                                    <span class="saas-kpi-title">Today's Visitors</span>
                                    <div class="saas-kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
                                        <i class="bx bx-user"></i>
                                    </div>
                                </div>
                                <div class="saas-kpi-value text-dark">{{ number_format($todayVisitors) }}</div>
                                <div class="saas-kpi-footer">
                                    <span class="saas-trend-badge {{ $todayGrowth >= 0 ? 'positive' : 'negative' }}">
                                        <i class="bx {{ $todayGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                        {{ abs($todayGrowth) }}%
                                    </span>
                                    <span>vs yesterday ({{ $yesterdayVisitors }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Yesterday's Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="saas-kpi-card h-100">
                                <div class="saas-kpi-header">
                                    <span class="saas-kpi-title">Yesterday's Visitors</span>
                                    <div class="saas-kpi-icon-box" style="background: #f1f5f9; color: #64748b;">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>
                                <div class="saas-kpi-value text-dark">{{ number_format($yesterdayVisitors) }}</div>
                                <div class="saas-kpi-footer">
                                    <span class="saas-trend-badge neutral">
                                        <i class="bx bx-time-five"></i>
                                        {{ now()->subDay()->format('d M') }}
                                    </span>
                                    <span>Recorded traffic</span>
                                </div>
                            </div>
                        </div>

                        <!-- All Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="saas-kpi-card h-100">
                                <div class="saas-kpi-header">
                                    <span class="saas-kpi-title">Total Visitors</span>
                                    <div class="saas-kpi-icon-box" style="background: #fef3c7; color: #d97706;">
                                        <i class="bx bx-group"></i>
                                    </div>
                                </div>
                                <div class="saas-kpi-value text-dark">{{ number_format($allVisitors) }}</div>
                                <div class="saas-kpi-footer">
                                    <span class="saas-trend-badge {{ $weekGrowth >= 0 ? 'positive' : 'negative' }}">
                                        <i class="bx {{ $weekGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                        {{ abs($weekGrowth) }}%
                                    </span>
                                    <span>Weekly pace</span>
                                </div>
                            </div>
                        </div>

                        <!-- New Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="saas-kpi-card h-100">
                                <div class="saas-kpi-header">
                                    <span class="saas-kpi-title">New Unique Visitors</span>
                                    <div class="saas-kpi-icon-box" style="background: #ecfdf5; color: #059669;">
                                        <i class="bx bx-user-plus"></i>
                                    </div>
                                </div>
                                <div class="saas-kpi-value text-dark">{{ number_format($newVisitors) }}</div>
                                <div class="saas-kpi-footer">
                                    <span class="saas-trend-badge positive">
                                        <i class="bx bx-check"></i>
                                        Active
                                    </span>
                                    <span>Unique IP sessions</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Overview & Order Status Pipeline -->
                    <div class="row g-4 mb-4">
                        <!-- Store Overview -->
                        <div class="col-lg-5">
                            <div class="saas-section-title">
                                <i class="bx bx-store text-primary fs-5"></i>
                                <span>Catalog & Order Counts</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <a href="{{ route('products') }}" class="text-decoration-none">
                                        <div class="saas-kpi-card h-100">
                                            <div class="saas-kpi-header">
                                                <span class="saas-kpi-title">Products</span>
                                                <div class="saas-kpi-icon-box" style="background: #ecfdf5; color: #006037;">
                                                    <i class="bx bx-package"></i>
                                                </div>
                                            </div>
                                            <div class="saas-kpi-value text-primary">{{ $totalProducts }}</div>
                                            <div class="saas-kpi-footer text-primary fw-medium">
                                                <span>Manage catalog →</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="{{ route('orders') }}" class="text-decoration-none">
                                        <div class="saas-kpi-card h-100">
                                            <div class="saas-kpi-header">
                                                <span class="saas-kpi-title">Total Orders</span>
                                                <div class="saas-kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
                                                    <i class="bx bx-shopping-bag"></i>
                                                </div>
                                            </div>
                                            <div class="saas-kpi-value text-success">{{ $totalOrders }}</div>
                                            <div class="saas-kpi-footer text-success fw-medium">
                                                <span>View all orders →</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Status Pipeline -->
                        <div class="col-lg-7">
                            <div class="saas-section-title">
                                <i class="bx bx-git-pull-request text-primary fs-5"></i>
                                <span>Order Fulfillment Pipeline</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 col-sm-3">
                                    <a href="{{ route('orders') }}" class="saas-status-card pending">
                                        <div class="saas-status-icon-wrap">
                                            <i class="bx bx-time-five"></i>
                                        </div>
                                        <div class="saas-status-count">{{ $ordersByStatus['pending'] }}</div>
                                        <p class="saas-status-label">Pending</p>
                                    </a>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <a href="{{ route('orders') }}" class="saas-status-card processing">
                                        <div class="saas-status-icon-wrap">
                                            <i class="bx bx-cog"></i>
                                        </div>
                                        <div class="saas-status-count">{{ $ordersByStatus['processing'] }}</div>
                                        <p class="saas-status-label">Processing</p>
                                    </a>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <a href="{{ route('orders') }}" class="saas-status-card completed">
                                        <div class="saas-status-icon-wrap">
                                            <i class="bx bx-check-circle"></i>
                                        </div>
                                        <div class="saas-status-count">{{ $ordersByStatus['completed'] }}</div>
                                        <p class="saas-status-label">Completed</p>
                                    </a>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <a href="{{ route('orders') }}" class="saas-status-card cancelled">
                                        <div class="saas-status-icon-wrap">
                                            <i class="bx bx-x-circle"></i>
                                        </div>
                                        <div class="saas-status-count">{{ $ordersByStatus['cancelled'] }}</div>
                                        <p class="saas-status-label">Cancelled</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders Quick Table (SaaS Component) -->
                    @if (isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="saas-table-card">
                                    <div class="saas-table-header">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">Recent Incoming Orders</h6>
                                            <small class="text-muted">Latest 5 orders placed by parents</small>
                                        </div>
                                        <a href="{{ route('orders') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            View All Orders
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table saas-table">
                                            <thead>
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Parent / Student</th>
                                                    <th>Campus</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentOrders as $order)
                                                    <tr>
                                                        <td>
                                                            <strong class="text-dark">#{{ $order->order_number }}</strong>
                                                            <small class="text-muted d-block" style="font-size: 0.72rem;">
                                                                {{ optional($order->created_at)->format('d M, h:i A') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $order->parent_name }}</div>
                                                            <small class="text-muted">{{ $order->student_name }} ({{ $order->class ?? '—' }})</small>
                                                        </td>
                                                        <td>
                                                            <span class="text-truncate d-inline-block" style="max-width: 180px;">
                                                                {{ $order->campus }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <strong class="text-dark">Rs. {{ number_format($order->total, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusClass = match($order->status) {
                                                                    'completed'  => 'bg-label-success',
                                                                    'processing' => 'bg-label-info',
                                                                    'cancelled'  => 'bg-label-danger',
                                                                    default      => 'bg-label-warning',
                                                                };
                                                            @endphp
                                                            <span class="badge saas-badge-pill {{ $statusClass }}">
                                                                {{ ucfirst($order->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $paymentClass = match($order->payment_status) {
                                                                    'paid'     => 'bg-label-success',
                                                                    'failed'   => 'bg-label-danger',
                                                                    'refunded' => 'bg-label-warning',
                                                                    default    => 'bg-label-secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge saas-badge-pill {{ $paymentClass }}">
                                                                {{ ucfirst($order->payment_status) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('orders') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1">
                                                                Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
