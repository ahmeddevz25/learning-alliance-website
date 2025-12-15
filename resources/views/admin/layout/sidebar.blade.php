<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('admin/assets/img/logo-right.png') }}" alt="" style="width: 8rem; margin: 20px;">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        @can('categories')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Categories</span>
        </li>
        <li class="menu-item {{ request()->routeIs('categories') ? 'active' : '' }}">
            <a href="{{ route('categories') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category-alt"></i>
                <div>Categories</div>
            </a>
        </li>
        @endcan

        @can('products')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Products</span>
        </li>
        <li class="menu-item {{ request()->routeIs('products') ? 'active' : '' }}">
            <a href="{{ route('products') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Products</div>
            </a>
        </li>
        @endcan

        @can('orders')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Orders</span>
        </li>
        <li class="menu-item {{ request()->routeIs('orders') ? 'active' : '' }}">
            <a href="{{ route('orders') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Orders</div>
            </a>
        </li>
        @endcan

        @can('instructions')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Instructions</span>
        </li>
        <li class="menu-item {{ request()->routeIs('instructionguides') ? 'active' : '' }}">
            <a href="{{ route('instructionguides') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-content"></i>
                <div>Instructions</div>
            </a>
        </li>
        @endcan

        @can('contactmessages')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Contact Message</span>
        </li>
        <li class="menu-item {{ request()->routeIs('contactmessages') ? 'active' : '' }}">
            <a href="{{ route('contactmessages') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div>Contact Message</div>
            </a>
        </li>
        @endcan

        @can('user management')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Users Management</span>
        </li>
        <li class="menu-item {{ request()->routeIs('users') ? 'active' : '' }}">
            <a href="{{ route('users') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Users</div>
            </a>
        </li>
        @endcan

        @can('role management')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Roles Management</span>
        </li>
        <li class="menu-item {{ request()->routeIs('roles') ? 'active' : '' }}">
            <a href="{{ route('roles') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div>Roles</div>
            </a>
        </li>
        @endcan

        @can('permission management')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Permissions Management</span>
        </li>
        <li class="menu-item {{ request()->routeIs('permissions') ? 'active' : '' }}">
            <a href="{{ route('permissions') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-alt"></i>
                <div>Permissions</div>
            </a>
        </li>
        @endcan

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Cache Clear</span>
        </li>
        <li class="menu-item {{ request()->routeIs('cacheclear') ? 'active' : '' }}">
            <a href="{{ route('cacheclear') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-refresh"></i>
                <div>Cache Clear</div>
            </a>
        </li>

    </ul>
</aside>
