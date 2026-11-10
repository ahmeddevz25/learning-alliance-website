<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Brand / Logo Area -->
    <div class="app-brand demo px-3 py-3">
        <div class="saas-brand-wrapper">
            <a href="{{ route('dashboard') }}" class="app-brand-link text-decoration-none">
                <img src="{{ asset('admin/assets/img/logo-right.png') }}" alt="Learning Alliance" class="saas-brand-logo">
            </a>
            <span class="saas-brand-badge">
                <span class="saas-pulse-dot"></span> Admin
            </span>
        </div>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none mt-2">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    @php
        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
        $totalMsgCount = \App\Models\ContactMessage::count();
    @endphp

    <ul class="menu-inner py-2">
        <!-- Main Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Store Management Section -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Catalog & Store</span>
        </li>

        @can('categories')
        <li class="menu-item {{ request()->routeIs('categories*') ? 'active' : '' }}">
            <a href="{{ route('categories') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Categories</div>
            </a>
        </li>
        @endcan

        @can('products')
        <li class="menu-item {{ request()->routeIs('products*') ? 'active' : '' }}">
            <a href="{{ route('products') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Products</div>
            </a>
        </li>
        @endcan

        @can('orders')
        <li class="menu-item {{ request()->routeIs('orders*') ? 'active' : '' }}">
            <a href="{{ route('orders') }}" class="menu-link d-flex align-items-center">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div>Orders</div>
                @if ($pendingOrdersCount > 0)
                    <span class="badge bg-warning text-dark ms-auto rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                        {{ $pendingOrdersCount }}
                    </span>
                @endif
            </a>
        </li>
        @endcan

        <!-- Content Section -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Communication</span>
        </li>

        @can('contactmessages')
        <li class="menu-item {{ request()->routeIs('contactmessages*') ? 'active' : '' }}">
            <a href="{{ route('contactmessages') }}" class="menu-link d-flex align-items-center">
                <i class="menu-icon tf-icons bx bx-chat"></i>
                <div>Messages</div>
                @if ($totalMsgCount > 0)
                    <span class="badge bg-info text-white ms-auto rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                        {{ $totalMsgCount }}
                    </span>
                @endif
            </a>
        </li>
        @endcan

        @can('instructions')
        <li class="menu-item {{ request()->routeIs('instructionguides*') ? 'active' : '' }}">
            <a href="{{ route('instructionguides') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-open"></i>
                <div>Size & Guides</div>
            </a>
        </li>
        @endcan

        <!-- System Settings Section -->
        @if(auth()->user()->can('user management') || auth()->user()->can('role management') || auth()->user()->can('permission management'))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Access Control</span>
        </li>

        @can('user management')
        <li class="menu-item {{ request()->routeIs('users*') ? 'active' : '' }}">
            <a href="{{ route('users') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Users</div>
            </a>
        </li>
        @endcan

        @can('role management')
        <li class="menu-item {{ request()->routeIs('roles*') ? 'active' : '' }}">
            <a href="{{ route('roles') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div>Roles</div>
            </a>
        </li>
        @endcan

        @can('permission management')
        <li class="menu-item {{ request()->routeIs('permissions*') ? 'active' : '' }}">
            <a href="{{ route('permissions') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-key"></i>
                <div>Permissions</div>
            </a>
        </li>
        @endcan
        @endif

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">System</span>
        </li>
        <li class="menu-item {{ request()->routeIs('cacheclear') ? 'active' : '' }}">
            <a href="{{ route('cacheclear') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-trash-alt"></i>
                <div>Clear Cache</div>
            </a>
        </li>

        <!-- Quick Storefront Link -->
        <li class="menu-item mt-3">
            <a href="{{ url('/') }}" target="_blank" class="menu-link" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                <i class="menu-icon tf-icons bx bx-link-external text-primary"></i>
                <div class="fw-bold text-primary">Live Storefront</div>
            </a>
        </li>
    </ul>

    <!-- Sidebar Bottom User Profile Card -->
    <div class="saas-sidebar-footer">
        <div class="saas-user-card">
            <div class="saas-avatar-initials">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-truncate" style="font-size: 0.82rem;">{{ Auth::user()->name }}</div>
                <div class="text-muted text-truncate" style="font-size: 0.7rem;">
                    {{ Auth::user()->getRoleNames()->first() ?? 'Staff' }}
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-icon btn-light text-muted p-1" title="Log Out" style="border:none;">
                    <i class="bx bx-power-off text-danger" style="font-size: 1.1rem;"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
