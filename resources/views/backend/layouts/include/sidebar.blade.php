<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('orders.analitices') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0"/>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"/>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0"/>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Laundry ERP</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 mt-0">
        {{-- Dashboard --}}
        <li class="menu-item">
            <a href="{{ route('orders.analitices') }}" class="menu-link">
                <i class="menu-icon ti ti-server"></i>
                <div data-i18n="Analytics">Analytics Dashboard</div>
            </a>
        </li>

        {{-- Clients --}}
        <li class="menu-item">
            <a href="{{ route('clientpage') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="Clients">Clients</div>
            </a>
        </li>

        {{-- Orders --}}
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('addOrder') }}" class="menu-link">
                        <div data-i18n="Add Order">Add Order</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('viewOrder') }}" class="menu-link">
                        <div data-i18n="View Orders">View Orders</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Categories --}}
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ti ti-list"></i>
                <div data-i18n="Categories">Categories</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('categories') }}" class="menu-link">
                        <div data-i18n="Categories List">Categories</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('categorylist') }}" class="menu-link">
                        <div data-i18n="Item Categories">Item Categories</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Services --}}
        <li class="menu-item">
            <a href="{{ route('services') }}" class="menu-link">
                <i class="menu-icon ti ti-service"></i>
                <div data-i18n="Services">Services</div>
            </a>
        </li>

        {{-- Items --}}
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ti ti-box"></i>
                <div data-i18n="Items">Items</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('items') }}" class="menu-link">
                        <div data-i18n="Items List">Items</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('add.items') }}" class="menu-link">
                        <div data-i18n="Add Item">Add Item</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('itemtype') }}" class="menu-link">
                        <div data-i18n="Item Types">Item Types</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Payments --}}
        <li class="menu-item">
            <a href="{{ route('payment') }}" class="menu-link">
                <i class="ti ti-credit-card menu-icon"></i>
                <div data-i18n="Payment">Payment</div>
            </a>
        </li>

        {{-- Invoice --}}
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="ti ti-receipt menu-icon"></i>
                <div data-i18n="Invoice">Invoice</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('invoice') }}" class="menu-link">
                        <div data-i18n="Invoices">Invoices</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('orders.export') }}" class="menu-link">
                        <div data-i18n="Export">Export</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Users --}}
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ti ti-users"></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('tenants.index') }}" class="menu-link">
                        <div data-i18n="Admin Users">Admin Users</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <div data-i18n="App Users">App Users</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                <i class="ti ti-sm"></i>
            </a>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ Auth::user()->image ? url('public/images/'.Auth::user()->image) : url('public/theam/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->image ? url('images/'.Auth::user()->image) : url('theam/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1 mt-3">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('myProfile') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('change.password') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
