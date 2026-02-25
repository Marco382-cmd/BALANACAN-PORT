<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Balanacan Port Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    <style>
       
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <span class="icon">⚓</span>
        <div>
            <div class="name">Balanacan Port</div>
            <div class="sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="ni-icon">📊</span> Dashboard
        </a>

        <div class="nav-section-label">Operations</div>
        <a href="{{ route('admin.routes.index') }}"
           class="nav-item {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
            <span class="ni-icon">🗺</span> Routes
        </a>
        <a href="{{ route('admin.trips.index') }}"
           class="nav-item {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
            <span class="ni-icon">⛴</span> Trips / Schedule
        </a>
        <a href="{{ route('admin.bookings.index') }}"
           class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <span class="ni-icon">🎫</span> Bookings
            @php
                $pendingCount = \App\Models\Booking::where('payment_status','pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="nav-section-label">Configuration</div>
        <a href="{{ route('admin.fares.index') }}"
           class="nav-item {{ request()->routeIs('admin.fares.*') ? 'active' : '' }}">
            <span class="ni-icon">💰</span> Fare Matrix
        </a>

        <div class="nav-section-label">Analytics</div>
        <a href="{{ route('admin.reports.index') }}"
           class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <span class="ni-icon">📈</span> Reports
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="user-name">{{ session('admin_name', 'Admin') }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Sign Out</button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="admin-main">
    <header class="admin-topbar">
        <button class="topbar-hamburger" id="hamburger" aria-label="Toggle menu">☰</button>
        <div class="topbar-breadcrumb">
            @yield('breadcrumb', '<strong>Dashboard</strong>')
        </div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" target="_blank" class="topbar-view-site">↗ View Site</a>
        </div>
    </header>

    <main class="admin-content">

        @if(session('success'))
           
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    document.getElementById('hamburger')?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
</script>
@stack('scripts')
</body>
</html>