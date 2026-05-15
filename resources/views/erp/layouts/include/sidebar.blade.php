<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#1F446E"/>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"/>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#1F446E"/>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Laundry ERP</span>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 mt-0">
        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon ti ti-chart-bar"></i>
                <div data-i18n="Analytics">Analytics Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('clientpage') }}" class="menu-link">
                <i class="menu-icon ti ti-users"></i>
                <div data-i18n="Clients">Clients</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ti ti-package"></i>
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
        <li class="menu-item">
            <a href="{{ route('services') }}" class="menu-link">
                <i class="menu-icon ti ti-tools"></i>
                <div data-i18n="Services">Services</div>
            </a>
        </li>
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
        <li class="menu-item">
            <a href="{{ route('payment') }}" class="menu-link">
                <i class="ti ti-credit-card menu-icon"></i>
                <div data-i18n="Payment">Payment</div>
            </a>
        </li>
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
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ti ti-user"></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('users.index') }}" class="menu-link">
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
