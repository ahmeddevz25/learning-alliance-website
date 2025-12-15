@extends('admin.layouts')
@section('content')
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <div class="row">
                            <div class="col-lg-12 mb-12 order-0 mb-3">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Congratulations
                                                    {{ Auth::user()->name }}! 🎉</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <h5 class="mb-3 fw-bold">Tracking Summary</h5> --}}
                        <div class="row g-4 mb-3">

                            <!-- Today's Visitors -->
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body">
                                        <p class="text-muted small mb-1">TODAY'S VISITORS</p>
                                        <h4 class="fw-bold">{{ $todayVisitors }}</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Yesterday {{ $yesterdayVisitors }}</small>
                                            <small class="{{ $todayGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $todayGrowth }}%
                                                <i
                                                    class="bx {{ $todayGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                            </small>
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ min(abs($todayGrowth), 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Yesterday's Visitors -->
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body">
                                        <p class="text-muted small mb-1">YESTERDAY'S VISITORS</p>
                                        <h4 class="fw-bold">{{ $yesterdayVisitors }}</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ now()->subDay()->format('d M') }}</small>
                                            <small class="text-danger">
                                                {{ $yesterdayVisitors > 0 ? '-87.5%' : '0%' }}
                                                <i class="bx bx-trending-down"></i>
                                            </small>
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar bg-danger" style="width: 40%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- All Visitors -->
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body">
                                        <p class="text-muted small mb-1">ALL VISITORS <span class="float-end">This
                                                Week</span></p>
                                        <h4 class="fw-bold">{{ $allVisitors }}</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Previous Week {{ $previousWeekVisitors }}</small>
                                            <small class="{{ $weekGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $weekGrowth }}%
                                                <i
                                                    class="bx {{ $weekGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                            </small>
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar {{ $weekGrowth >= 0 ? 'bg-success' : 'bg-danger' }}"
                                                style="width: {{ min(abs($weekGrowth), 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New Visitors -->
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body">
                                        <p class="text-muted small mb-1">NEW VISITORS <span class="float-end">This
                                                Week</span></p>
                                        <h4 class="fw-bold">{{ $newVisitors }}</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Previous Week {{ $previousWeekVisitors }}</small>
                                            <small class="text-danger">
                                                -51.52% <i class="bx bx-trending-down"></i>
                                            </small>
                                        </div>
                                        <div class="progress mt-2" style="height:3px;">
                                            <div class="progress-bar bg-danger" style="width: 50%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Total Products -->
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-box-open fa-2x text-primary mb-2"></i>
                                        <h6 class="text-primary fw-bold">Total Products</h6>
                                        <h3 class="fw-bold mt-2">{{ $totalProducts }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                        <h6 class="text-success fw-bold">Total Orders</h6>
                                        <h3 class="fw-bold mt-2">{{ $totalOrders }}</h3>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Orders Status in Single Row -->
                        <div class="row text-center">
                            <div class="col-md-3 col-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body">
                                        <i class="fas fa-hourglass-half fa-lg text-warning mb-2"></i>
                                        <h6 class="fw-bold text-warning">Pending</h6>
                                        <h4 class="fw-bold">{{ $ordersByStatus['pending'] }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body">
                                        <i class="fas fa-cogs fa-lg text-primary"></i>
                                        <h6 class="fw-bold text-primary">Processing</h6>
                                        <h4 class="fw-bold">{{ $ordersByStatus['processing'] }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body">
                                        <i class="fas fa-flag-checkered fa-lg text-success"></i>
                                        <h6 class="fw-bold text-success">Completed</h6>
                                        <h4 class="fw-bold">{{ $ordersByStatus['completed'] }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="card shadow-sm border-0 rounded-3 h-100 hover-card">
                                    <div class="card-body">
                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                        <h6 class="fw-bold text-danger">Cancelled</h6>
                                        <h4 class="fw-bold">{{ $ordersByStatus['cancelled'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection


