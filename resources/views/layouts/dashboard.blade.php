<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        body {
            background: #0f0f0f;
            color: #f1f1f1;
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            background: #111111;
            border-right: 1px solid #222;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 40;
        }

        /* Mobile: sidebar hidden off-screen by default */
        @media (max-width: 1023px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #sidebar-overlay.open { display: block; }
        }

        /* ===== MAIN CONTENT ===== */
        #main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 1023px) {
            #main { margin-left: 0; }
        }

        /* ===== TOP BAR ===== */
        #topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background: #111;
            border-bottom: 1px solid #222;
            padding: 0 1rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* ===== NAV LINKS ===== */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            color: #999;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .nav-link:hover { background: #1a1a1a; color: #fff; }
        .nav-link.active { background: #1d4ed8; color: #fff; }
        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #555;
            padding: 14px 14px 6px;
        }

        /* ===== BOTTOM NAV (Mobile) ===== */
        #bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #111;
            border-top: 1px solid #222;
            z-index: 50;
            padding: 8px 4px;
            padding-bottom: max(8px, env(safe-area-inset-bottom));
        }
        #bottom-nav-inner {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
        }
        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 6px 4px;
            border-radius: 8px;
            color: #666;
            text-decoration: none;
            font-size: 10px;
            transition: all 0.15s;
        }
        .bnav-item svg { width: 20px; height: 20px; }
        .bnav-item.active { color: #3b82f6; }
        .bnav-item:hover { color: #fff; background: #1a1a1a; }

        @media (max-width: 1023px) {
            #bottom-nav { display: block; }
            #main { padding-bottom: 70px; }
        }

        /* ===== PAGE CONTENT ===== */
        #page-content {
            flex: 1;
            padding: 1.5rem;
        }
        @media (max-width: 640px) {
            #page-content { padding: 1rem; }
        }

        /* ===== FLASH MESSAGES ===== */
        .flash { border-radius: 10px; padding: 12px 16px; margin-bottom: 1rem; font-size: 14px; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .flash-success { background: #052e16; color: #86efac; border: 1px solid #166534; }
        .flash-error   { background: #2d0a0a; color: #fca5a5; border: 1px solid #7f1d1d; }

        /* ===== HAMBURGER ===== */
        #hamburger { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 8px; color: #ccc; }
        #hamburger:hover { background: #1a1a1a; }
        #hamburger svg { width: 22px; height: 22px; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 99px; }
    </style>
</head>
<body>

{{-- ===== SIDEBAR OVERLAY ===== --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ===== SIDEBAR ===== --}}
<aside id="sidebar">

    {{-- Logo --}}
    <div style="padding: 20px 16px 16px; border-bottom: 1px solid #1f1f1f;">
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:36px;height:36px;background:#1d4ed8;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">🍽</div>
            <div>
                <p style="font-weight:700;font-size:15px;margin:0;color:#fff;">QR Menu</p>
                <p style="font-size:11px;color:#666;margin:0;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ auth()->user()->restaurant->name ?? 'Dashboard' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav style="flex:1;overflow-y:auto;padding:10px 10px;">

        <a href="{{ route('dashboard.home') }}"
           class="nav-link {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <p class="nav-label">Menu</p>

        <a href="{{ route('dashboard.categories.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Categories
        </a>

        <a href="{{ route('dashboard.products.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Products
        </a>

        <p class="nav-label">QR Codes</p>

        <a href="{{ route('dashboard.qr-codes.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.qr-codes.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            QR Codes
        </a>

        <p class="nav-label">Restaurant</p>

        {{-- <a href="{{ route('dashboard.branches.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.branches.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Branches
        </a> --}}

        {{-- <a href="{{ route('staff.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.staff.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Staff
        </a> --}}

        <a href="{{ route('dashboard.subscription.index') }}"
           class="nav-link {{ request()->routeIs('dashboard.subscription.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Subscription
            @if(!auth()->user()->restaurant?->hasActiveSubscription() && !auth()->user()->hasRole('super_admin'))
                <span style="margin-left:auto;width:7px;height:7px;background:#facc15;border-radius:99px;"></span>
            @endif
        </a>

        <p class="nav-label">Preview</p>

        @if(auth()->user()->restaurant)
        <a href="{{ url('/r/' . auth()->user()->restaurant->slug) }}" target="_blank"
           class="nav-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View Public Menu ↗
        </a>
        @endif

        <p class="nav-label">Settings</p>
        <a href="{{ route('dashboard.profile.index') }}"
            class="nav-link {{ request()->routeIs('dashboard.profile.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile & Settings
        </a>


    </nav>

    {{-- User Footer --}}
    <div style="padding:12px 10px;border-top:1px solid #1f1f1f;">
       <a href="{{ route('dashboard.profile.index') }}" style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;text-decoration:none;margin-bottom:8px;transition:background .15s;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
            {{-- Avatar --}}
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                    style="width:34px;height:34px;border-radius:99px;object-fit:cover;border:1px solid #2a2a2a;flex-shrink:0;">
            @else
                <div style="width:34px;height:34px;border-radius:99px;background:#1d4ed8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="overflow:hidden;">
                <p style="font-size:13px;font-weight:600;color:#fff;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ auth()->user()->name }}
                </p>
                <p style="font-size:11px;color:#555;margin:0;">Edit Profile</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link" style="width:100%;border:none;cursor:pointer;background:none;text-align:left;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

</aside>

{{-- ===== MAIN ===== --}}
<div id="main">

    {{-- Top Bar --}}
    <header id="topbar">
        {{-- Hamburger (mobile only) --}}
        <button id="hamburger" onclick="openSidebar()" aria-label="Open menu" class="lg:hidden">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page Title --}}
        <h1 style="font-size:16px;font-weight:600;color:#fff;margin:0;flex:1;">
            @yield('page-title', 'Dashboard')
        </h1>

        {{-- Right side --}}
        <div style="display:flex;align-items:center;gap:8px;">
            @if(auth()->user()->restaurant)
            <a href="{{ url('/r/' . auth()->user()->restaurant->slug) }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:8px;color:#999;font-size:12px;text-decoration:none;white-space:nowrap;"
               class="hidden sm:inline-flex">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Menu ↗
            </a>
            @endif
        </div>
    </header>

    {{-- Flash Messages --}}
    <div style="padding: 0.75rem 1.5rem 0;">
        @if(session('success'))
            <div class="flash flash-success">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:18px;line-height:1;">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:18px;line-height:1;">&times;</button>
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main id="page-content">
        @yield('content')
    </main>

</div>

{{-- ===== BOTTOM NAV (mobile) ===== --}}
<nav id="bottom-nav" aria-label="Mobile navigation">
    <div id="bottom-nav-inner">

        <a href="{{ route('dashboard.home') }}"
           class="bnav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>

        <a href="{{ route('dashboard.products.index') }}"
           class="bnav-item {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Products
        </a>

        <a href="{{ route('dashboard.qr-codes.index') }}"
           class="bnav-item {{ request()->routeIs('dashboard.qr-codes.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            QR Codes
        </a>

        <a href="{{ route('dashboard.categories.index') }}"
           class="bnav-item {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Categories
        </a>

        <button onclick="openSidebar()" class="bnav-item" style="background:none;border:none;cursor:pointer;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16"/></svg>
            More
        </button>

    </div>
</nav>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
// Close on ESC
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
</script>

</body>
</html>