<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand demo">
		<a href="{{route('admin.dashboard')}}" class="app-brand-link">
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

	<ul class="menu-inner py-1">
		<li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : ''}}">
			<a href="{{route('admin.dashboard')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-home-circle"></i>
				<div data-i18n="Dashboard">Dashboard</div>
			</a>
		</li>

		<li class="menu-item {{ request()->is('admin/users') ? 'active' : ''}}">
			<a href="{{route('admin.users.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-group"></i>
				<div data-i18n="User">Users</div>
			</a>
		</li>

		<li class="menu-item {{ request()->is('admin/contacts') ? 'active' : ''}}">
			<a href="{{route('admin.contacts.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-envelope"></i>
				<div data-i18n="Contacts">Contacts</div>
			</a>
		</li>

		<li class="menu-item {{ request()->is('admin/notifications/index') ? 'active' : ''}}">
			<a href="{{route('admin.notifications.index')}}" class="menu-link">
				<i class="menu-icon tf-icons bx bx-bell"></i>
				<div data-i18n="Notifications">Notifications</div>
			</a>
		</li>
		
		@php
            $pages = Helper::pages();
        @endphp
		
		<li class="menu-item {{ request()->is('admin/page*') ? 'active open' : ''}}">
			<a href="javascript:void(0);" class="menu-link menu-toggle">
				<i class="menu-icon tf-icons bx bx-book-content"></i>
				<div data-i18n="Pages">Pages</div>
				<div class="badge bg-danger rounded-pill ms-auto">{{count($pages)}}</div>
			</a>
			<ul class="menu-sub">
				@foreach($pages as $page)
					<li class="menu-item {{ request()->is('admin/page/create/'.$page->key) ? 'active' : ''}}">
						<a href="{{route('admin.page.create',$page->key)}}" class="menu-link">
							<div data-i18n="{{$page->name}}">{{$page->name}}</div>
						</a>
					</li>
                @endforeach
			</ul>
		</li>
		
	</ul>
</aside>
