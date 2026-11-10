<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4 text-secondary" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100" id="navbar-collapse">
        <!-- SaaS Search Input -->
        <div class="saas-search-container">
            <i class="bx bx-search saas-search-icon"></i>
            <input type="text" id="global-search" class="form-control saas-search-input"
                placeholder="Search orders, products..." autocomplete="off">
            <span class="saas-search-kbd">⌘K</span>
            <ul id="search-results" class="list-group position-absolute w-100 mt-2 shadow-lg"
                style="z-index: 1050; display: none; max-height: 280px; overflow-y: auto; border-radius: 12px; border: 1px solid #e2e8f0;"></ul>
        </div>

        <!-- Right Side Utility Badges & User Profile -->
        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
            <!-- Store Live Indicator -->
            <li class="nav-item d-none d-md-flex align-items-center me-2">
                <a href="{{ url('/') }}" target="_blank" class="badge bg-label-success px-3 py-2 text-decoration-none rounded-pill d-flex align-items-center gap-2">
                    <span class="saas-pulse-dot" style="background-color: #10b981;"></span>
                    <span class="fw-semibold">Store Live</span>
                    <i class="bx bx-link-external" style="font-size: 0.85rem;"></i>
                </a>
            </li>

            <!-- Quick Add Product Action -->
            @can('product add')
            <li class="nav-item d-none d-sm-block me-2">
                <a href="{{ route('products') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i>
                    <span>Product</span>
                </a>
            </li>
            @endcan

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="saas-avatar-initials" style="width: 38px; height: 38px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,96,55,0.2);">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2" style="border-radius: 14px; min-width: 230px;">
                    <!-- User Info Card -->
                    <li class="px-2 py-2 mb-1" style="background: #f8fafc; border-radius: 10px;">
                        <div class="d-flex align-items-center">
                            <div class="saas-avatar-initials me-2" style="width: 34px; height: 34px; font-size: 0.8rem;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <span class="fw-bold d-block text-truncate" style="font-size: 0.85rem;">{{ Auth::user()->name }}</span>
                                <small class="badge bg-label-primary px-2 py-0" style="font-size: 0.68rem;">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'Admin' }}
                                </small>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a class="dropdown-item rounded-3 py-2" href="{{ route('orders') }}">
                            <i class="bx bx-shopping-bag me-2 text-primary"></i>
                            <span class="align-middle">Orders</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2" href="{{ route('products') }}">
                            <i class="bx bx-package me-2 text-primary"></i>
                            <span class="align-middle">Products</span>
                        </a>
                    </li>
                    @can('user management')
                    <li>
                        <a class="dropdown-item rounded-3 py-2" href="{{ route('users') }}">
                            <i class="bx bx-user me-2 text-primary"></i>
                            <span class="align-middle">Staff Users</span>
                        </a>
                    </li>
                    @endcan

                    <li><hr class="dropdown-divider my-2"></li>

                    <!-- Logout -->
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle fw-medium">Log Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    $(document).ready(function() {
        $('#global-search').on('keyup', function() {
            let query = $(this).val();

            if (query.length < 2) {
                $('#search-results').hide().empty();
                return;
            }

            $.ajax({
                url: "{{ route('global.search') }}",
                type: "GET",
                data: { q: query },
                success: function(data) {
                    let list = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            list += `
                            <li class="list-group-item list-group-item-action py-2">
                                <a href="${item.url}" class="d-flex justify-content-between align-items-center text-decoration-none text-dark">
                                    <span class="fw-medium">${item.label}</span>
                                    <span class="badge bg-label-secondary">${item.type}</span>
                                </a>
                            </li>`;
                        });
                    } else {
                        list = '<li class="list-group-item py-3 text-muted text-center" style="font-size:0.85rem;">No matching results found</li>';
                    }
                    $('#search-results').html(list).show();
                }
            });
        });

        // Hide results if clicked outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.saas-search-container').length) {
                $('#search-results').hide();
            }
        });

        // Keyboard shortcut: Ctrl + K or Cmd + K focuses search
        $(document).on('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                $('#global-search').focus();
            }
        });
    });
</script>
