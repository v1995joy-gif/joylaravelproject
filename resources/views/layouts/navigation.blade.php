@php
    $navLinkStyle = 'display:flex; align-items:center; gap:12px; padding:10px 14px; margin-bottom:4px; border-radius:8px; color:#9ca3af; text-decoration:none; font-size:13.5px; font-weight:500; transition:all 0.2s ease;';
    $navIconStyle = 'display:inline-flex; width:18px; height:18px; color:#6b7280; flex-shrink:0; transition:color 0.2s ease;';
    $navLabelStyle = 'font-size:10px; color:#6b7280; font-weight:700; margin:0 14px 8px; text-transform:uppercase; letter-spacing:0.05em;';

    $adminSections = [
        'Main' => [
            ['route' => 'admin.dashboard', 'patterns' => ['admin.dashboard'], 'icon' => 'lucide-layout-dashboard', 'label' => 'Dashboard'],
        ],
        'Inventory' => [
            ['route' => 'admin.rental-items.index', 'patterns' => ['admin.rental-items.*'], 'icon' => 'lucide-shirt', 'label' => 'Rental Items'],
            ['route' => 'admin.item-categories.index', 'patterns' => ['admin.item-categories.*'], 'icon' => 'lucide-tags', 'label' => 'Item Categories'],
            ['route' => 'admin.packages.index', 'patterns' => ['admin.packages.*'], 'icon' => 'lucide-package', 'label' => 'Packages'],
        ],
        'Management' => [
            ['route' => 'admin.customers.index', 'patterns' => ['admin.customers.*'], 'icon' => 'lucide-users', 'label' => 'Customers'],
            ['route' => 'admin.customization-requests.index', 'patterns' => ['admin.customization-requests.*'], 'icon' => 'lucide-scissors', 'label' => 'Customization Requests'],
            ['route' => 'admin.reservations.index', 'patterns' => ['admin.reservations.*'], 'icon' => 'lucide-calendar-check', 'label' => 'Reservations'],
            ['route' => 'admin.return-schedule.index', 'patterns' => ['admin.return-schedule.*'], 'icon' => 'lucide-calendar-clock', 'label' => 'Return Schedule'],
            ['route' => 'admin.appointments.index', 'patterns' => ['admin.appointments.*'], 'icon' => 'lucide-calendar-days', 'label' => 'Appointments'],
            ['route' => 'admin.payments.index', 'patterns' => ['admin.payments.*'], 'icon' => 'lucide-wallet', 'label' => 'Payments'],
            ['route' => 'admin.reports.index', 'patterns' => ['admin.reports.*'], 'icon' => 'lucide-chart-column', 'label' => 'Reports & Transactions'],
        ],
        'Notifications' => [
            ['route' => 'admin.notifications.index', 'patterns' => ['admin.notifications.*'], 'icon' => 'lucide-bell', 'label' => 'Notification & Reminder Management'],
        ],
    ];

    $customerSections = [
        'Main' => [
            ['route' => 'dashboard', 'patterns' => ['dashboard'], 'icon' => 'lucide-layout-dashboard', 'label' => 'Dashboard'],
        ],
        'Catalog' => [
            ['route' => 'customer.item-categories.index', 'patterns' => ['customer.item-categories.*'], 'icon' => 'lucide-tags', 'label' => 'Item Categories'],
            ['route' => 'customer.packages.index', 'patterns' => ['customer.packages.*'], 'icon' => 'lucide-shopping-bag', 'label' => 'Packages'],
        ],
        'My Activities' => [
            ['route' => 'customer.reservations.index', 'patterns' => ['customer.reservations.*'], 'icon' => 'lucide-calendar-check', 'label' => 'My Reservations'],
            ['route' => 'customer.appointments.index', 'patterns' => ['customer.appointments.*'], 'icon' => 'lucide-calendar-days', 'label' => 'My Appointments'],
            ['route' => 'customer.customizations.index', 'patterns' => ['customer.customizations.index', 'customer.customizations.show', 'customer.customizations.create', 'customer.customizations.store'], 'icon' => 'lucide-scissors', 'label' => 'My Customizations'],
            ['route' => 'customer.payments.index', 'patterns' => ['customer.payments.*'], 'icon' => 'lucide-wallet', 'label' => 'My Payments'],
            ['route' => 'customer.customization.portal', 'patterns' => ['customer.customization.*', 'customer.customizations.custom-set*'], 'icon' => 'lucide-ruler', 'label' => 'Customization'],
        ],
    ];

    $navSections = Auth::user()->role === 'admin' ? $adminSections : $customerSections;

    $pageTitle = null;
    foreach ($navSections as $items) {
        foreach ($items as $item) {
            if (request()->routeIs(...$item['patterns'])) {
                $pageTitle = $item['label'];
                break 2;
            }
        }
    }
    $pageTitle ??= request()->routeIs('profile.*') ? 'Profile' : 'Dashboard';
@endphp

@include('partials.theme-script')

<nav>
    <div class="admin-sidebar"
        style="position:fixed; top:0; left:0; width:260px; height:100vh; background:#0f172a; color:white; z-index:9999; overflow-y:auto; border-right:1px solid rgba(255,255,255,0.05); box-shadow:4px 0 24px rgba(0,0,0,0.1);">

        <!-- Brand -->
        <div style="padding:24px 20px; border-bottom:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex;align-items:center;gap:12px;">
                <x-application-logo style="width:40px;height:40px;flex:0 0 40px;object-fit:contain;border-radius:50%;background:#fff;border:2px solid #c9a35f;" />
                <div class="admin-sidebar-brand-copy">
                    <div style="font-family:'Playfair Display', Georgia, serif; font-size:18px; font-weight:700; color:#fff; letter-spacing:0.02em;">Maribel Gowns</div>
                    <div style="font-size:9.5px; color:#c9a35f; margin-top:4px; letter-spacing:0.15em; font-weight:700;">BOUTIQUE &amp; FITTING</div>
                </div>
            </div>
        </div>

        <div style="padding:20px 12px;">

            @foreach ($navSections as $sectionLabel => $items)
                <div style="margin-bottom:24px;">
                    <div style="{{ $navLabelStyle }}">{{ $sectionLabel }}</div>

                    @foreach ($items as $item)
                        @php $isActive = request()->routeIs(...$item['patterns']); @endphp
                        <a href="{{ route($item['route']) }}"
                            aria-current="{{ $isActive ? 'page' : 'false' }}"
                            style="{{ $navLinkStyle }} background:{{ $isActive ? 'rgba(201, 163, 95, 0.1)' : 'transparent' }}; color:{{ $isActive ? '#c9a35f' : '#9ca3af' }}; border-left:3px solid {{ $isActive ? '#c9a35f' : 'transparent' }};">
                            <span style="{{ $navIconStyle }} color:{{ $isActive ? '#c9a35f' : '#6b7280' }};">
                                <x-dynamic-component :component="$item['icon']" width="18" height="18" aria-hidden="true" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach

            <div>
                <div style="{{ $navLabelStyle }}">Account</div>

                <a href="{{ route('profile.edit') }}"
                    aria-current="{{ request()->routeIs('profile.*') ? 'page' : 'false' }}"
                    style="{{ $navLinkStyle }} background:{{ request()->routeIs('profile.*') ? 'rgba(201, 163, 95, 0.1)' : 'transparent' }}; color:{{ request()->routeIs('profile.*') ? '#c9a35f' : '#9ca3af' }}; border-left:3px solid {{ request()->routeIs('profile.*') ? '#c9a35f' : 'transparent' }};">
                    <span style="{{ $navIconStyle }} color:{{ request()->routeIs('profile.*') ? '#c9a35f' : '#6b7280' }};">
                        <x-lucide-user width="18" height="18" aria-hidden="true" />
                    </span>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ea-logout-form">
                    @csrf
                    <button type="button" onclick="eaOpenLogout(this)"
                        style="{{ $navLinkStyle }} width:100%; text-align:left; border:none; background:transparent; cursor:pointer; font-family:inherit;">
                        <span style="{{ $navIconStyle }}">
                            <x-lucide-log-out width="18" height="18" aria-hidden="true" />
                        </span>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>

<header class="topbar">
    <div class="topbar-title">
        @isset($header)
            {{ $header }}
        @else
            {{ $pageTitle }}
        @endisset
    </div>

    <div class="topbar-actions">
        <x-theme-toggle />

        @if (in_array(Auth::user()->role, ['admin', 'customer'], true))
            <details class="admin-notification-menu" style="position:relative;" data-storage-key="{{ $notificationStorageKey }}" data-notification-version="{{ $notificationVersion }}">
                <summary class="topbar-icon-btn admin-notification-trigger" style="list-style:none; position:relative;" title="Notifications" aria-label="Notifications">
                    <x-lucide-bell width="16" height="16" aria-hidden="true" />
                    @if ($notificationCount > 0)
                        <span class="admin-notification-count" style="align-items:center; background:#c2413b; border:2px solid var(--header-bg); border-radius:999px; color:#fff; display:flex; font-size:9px; font-weight:700; height:17px; justify-content:center; line-height:1; min-width:17px; padding:0 3px; position:absolute; right:-5px; top:-5px;">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
                    @endif
                </summary>

                <div class="admin-notification-dropdown" style="background:var(--neo-bg); border:1px solid var(--event-line); border-radius:8px; box-shadow:var(--neo-raise); max-height:min(440px, calc(100vh - 90px)); overflow-y:auto; position:absolute; right:0; top:calc(100% + 12px); width:min(360px, calc(100vw - 24px)); z-index:10000;" aria-label="Notifications">
                    <div class="admin-notification-heading" style="align-items:center; border-bottom:1px solid var(--event-line); color:var(--event-ink); display:flex; justify-content:space-between; padding:14px 16px;">
                        <strong>{{ $notificationHeading }}</strong>
                        <span style="color:var(--event-muted); font-size:11px;">{{ $notificationSummary }}</span>
                    </div>

                    @forelse ($notifications as $notification)
                        <a class="admin-notification-item" style="align-items:flex-start; border-bottom:1px solid var(--event-line); color:var(--event-ink); display:flex; gap:10px; padding:12px 16px; text-decoration:none;" href="{{ $notification['url'] }}">
                            <span class="admin-notification-dot" aria-hidden="true"></span>
                            <span class="admin-notification-copy" style="display:grid; gap:3px; min-width:0;">
                                <strong>{{ $notification['title'] }}</strong>
                                <span>{{ $notification['detail'] }}</span>
                                <small>{{ $notification['created_at']?->diffForHumans() ?? 'Recently' }}</small>
                            </span>
                        </a>
                    @empty
                        <p class="admin-notification-empty">{{ $notificationEmptyMessage }}</p>
                    @endforelse
                </div>
            </details>
        @endif

        <a href="{{ route('profile.edit') }}" class="topbar-icon-btn" title="Profile" aria-label="Profile">
            <x-lucide-user width="16" height="16" aria-hidden="true" />
        </a>

        <form method="POST" action="{{ route('logout') }}" class="ea-logout-form" style="margin:0;">
            @csrf
            <button type="button" onclick="eaOpenLogout(this)" class="topbar-icon-btn" title="Log out" aria-label="Log out">
                <x-lucide-log-out width="16" height="16" aria-hidden="true" />
            </button>
        </form>
    </div>
</header>

<div class="ea-logout-overlay" id="eaLogoutOverlay" aria-hidden="true">
    <div class="ea-logout-modal" role="dialog" aria-modal="true" aria-labelledby="eaLogoutTitle">
        <div class="ea-logout-icon">
            <x-lucide-log-out width="22" height="22" aria-hidden="true" />
        </div>
        <h3 id="eaLogoutTitle">Log out of Maribel Gowns Boutique?</h3>
        <p>You'll need to sign back in to manage rentals and appointments.</p>
        <div class="ea-logout-actions">
            <button type="button" class="ea ea-logout-cancel" onclick="eaCloseLogout()">Cancel</button>
            <button type="button" class="ea ea-logout-yes" onclick="eaConfirmLogout()">Yes, Log Out</button>
        </div>
    </div>
</div>

<script>
    const notificationMenu = document.querySelector('.admin-notification-menu');

    if (notificationMenu) {
        const notificationBadge = notificationMenu.querySelector('.admin-notification-count');
        const storageKey = notificationMenu.dataset.storageKey;
        const notificationVersion = notificationMenu.dataset.notificationVersion;

        try {
            if (localStorage.getItem(storageKey) === notificationVersion) {
                notificationBadge?.remove();
            }
        } catch (error) {
            // Keep the badge visible when browser storage is unavailable.
        }

        notificationMenu.addEventListener('toggle', () => {
            if (!notificationMenu.open) return;

            notificationBadge?.remove();

            try {
                localStorage.setItem(storageKey, notificationVersion);
            } catch (error) {
                // The badge is still dismissed for the current page view.
            }
        });
    }

    let eaLogoutForm = null;

    function eaOpenLogout(btn) {
        eaLogoutForm = btn.closest('.ea-logout-form');
        const overlay = document.getElementById('eaLogoutOverlay');
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
    }

    function eaCloseLogout() {
        const overlay = document.getElementById('eaLogoutOverlay');
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        eaLogoutForm = null;
    }

    function eaConfirmLogout() {
        if (eaLogoutForm) eaLogoutForm.submit();
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') eaCloseLogout();
    });

    document.getElementById('eaLogoutOverlay').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) eaCloseLogout();
    });
</script>
