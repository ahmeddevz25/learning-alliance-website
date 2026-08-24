@extends('admin.layouts')
@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="container-xxl flex-grow-1 container-p-y">

                    <!-- Congratulations Card -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-primary text-white shadow border-0 overflow-hidden"
                                style="position: relative;">
                                <div class="card-body p-5">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 text-center text-md-start z-1">
                                            <h3 class="card-title text-white mb-2 fw-bold">Congratulations
                                                {{ Auth::user()->name }}! 🎉</h3>
                                            <p class="mb-4 fs-6 opacity-75">
                                                You have <span
                                                    class="fw-bold text-white">{{ $ordersByStatus['pending'] }}</span>
                                                pending orders today. Check your dashboard regularly to stay updated with
                                                your store's performance.
                                            </p>
                                            <a href="{{ url('orders') }}"
                                                class="btn btn-light text-primary fw-bold px-4 shadow-sm">View Orders</a>
                                        </div>
                                        <div class="col-md-4 text-center d-none d-md-block z-1">
                                            <i class='bx bx-trophy'
                                                style="font-size: 8rem; opacity: 0.2; transform: rotate(15deg);"></i>
                                        </div>
                                    </div>
                                    <!-- Decorative background shapes -->
                                    <div
                                        style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; blur(10px);">
                                    </div>
                                    <div
                                        style="position: absolute; bottom: -50%; left: -5%; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="pb-1 mb-3 fw-bold text-muted">Visitor Analytics</h5>
                    <div class="row g-4 mb-5">
                        <!-- Today's Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h3 class="mb-1 fw-bold text-dark">{{ $todayVisitors }}</h3>
                                            <span class="text-muted fw-medium">Today's Visitors</span>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-label-primary p-3"><i
                                                    class="bx bx-user fs-4"></i></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-{{ $todayGrowth >= 0 ? 'success' : 'danger' }} me-2">
                                            <i
                                                class="bx {{ $todayGrowth >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                            {{ abs($todayGrowth) }}%
                                        </span>
                                        <small class="text-muted">vs yesterday ({{ $yesterdayVisitors }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Yesterday's Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h3 class="mb-1 fw-bold text-dark">{{ $yesterdayVisitors }}</h3>
                                            <span class="text-muted fw-medium">Yesterday's Visitors</span>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-label-secondary p-3"><i
                                                    class="bx bx-user-pin fs-4"></i></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-secondary me-2">
                                            <i class="bx bx-calendar"></i>
                                        </span>
                                        <small class="text-muted">{{ now()->subDay()->format('d M, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h3 class="mb-1 fw-bold text-dark">{{ $allVisitors }}</h3>
                                            <span class="text-muted fw-medium">Total Visitors</span>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-label-info p-3"><i
                                                    class="bx bx-group fs-4"></i></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-{{ $weekGrowth >= 0 ? 'success' : 'danger' }} me-2">
                                            <i
                                                class="bx {{ $weekGrowth >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                            {{ abs($weekGrowth) }}%
                                        </span>
                                        <small class="text-muted">vs last week</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Visitors -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h3 class="mb-1 fw-bold text-dark">{{ $newVisitors }}</h3>
                                            <span class="text-muted fw-medium">New Visitors</span>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-label-success p-3"><i
                                                    class="bx bx-user-plus fs-4"></i></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-primary me-2">This Week</span>
                                        <small class="text-muted">Unique sessions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <!-- Store Overview -->
                        <div class="col-lg-6">
                            <h5 class="pb-1 mb-3 fw-bold text-muted">Store Overview</h5>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted fw-medium">Total Products</p>
                                                <h3 class="mb-0 fw-bold text-primary">{{ $totalProducts }}</h3>
                                            </div>
                                            <div class="avatar">
                                                <span class="avatar-initial rounded bg-label-primary"><i
                                                        class="bx bx-box fs-3"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted fw-medium">Total Orders</p>
                                                <h3 class="mb-0 fw-bold text-success">{{ $totalOrders }}</h3>
                                            </div>
                                            <div class="avatar">
                                                <span class="avatar-initial rounded bg-label-success"><i
                                                        class="bx bx-cart fs-3"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Statuses -->
                        <div class="col-lg-6">
                            <h5 class="pb-1 mb-3 fw-bold text-muted">Order Statuses</h5>
                            <div class="row g-3">
                                <div class="col-6 col-sm-3">
                                    <div class="card shadow-sm border-0 h-100 text-center">
                                        <div class="card-body p-3">
                                            <div class="avatar mx-auto mb-2">
                                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                        class="bx bx-time-five fs-5"></i></span>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-warning">{{ $ordersByStatus['pending'] }}</h6>
                                            <small class="text-muted fw-medium">Pending</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card shadow-sm border-0 h-100 text-center">
                                        <div class="card-body p-3">
                                            <div class="avatar mx-auto mb-2">
                                                <span class="avatar-initial rounded-circle bg-label-info"><i
                                                        class="bx bx-cog fs-5"></i></span>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-info">{{ $ordersByStatus['processing'] }}</h6>
                                            <small class="text-muted fw-medium">Processing</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card shadow-sm border-0 h-100 text-center">
                                        <div class="card-body p-3">
                                            <div class="avatar mx-auto mb-2">
                                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                                        class="bx bx-check-circle fs-5"></i></span>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-success">{{ $ordersByStatus['completed'] }}</h6>
                                            <small class="text-muted fw-medium">Completed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card shadow-sm border-0 h-100 text-center">
                                        <div class="card-body p-3">
                                            <div class="avatar mx-auto mb-2">
                                                <span class="avatar-initial rounded-circle bg-label-danger"><i
                                                        class="bx bx-x-circle fs-5"></i></span>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-danger">{{ $ordersByStatus['cancelled'] }}</h6>
                                            <small class="text-muted fw-medium">Cancelled</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
