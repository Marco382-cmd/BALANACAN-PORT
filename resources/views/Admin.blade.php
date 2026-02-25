<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Balanacan Port</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #0f2d4a;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-weight: 800;
            font-size: 1rem;
            line-height: 1.3;
        }
        .sidebar-brand span { display: block; font-size: .72rem; font-weight: 400; opacity: .6; margin-top: .15rem; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .sidebar-label {
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,.4);
            padding: .75rem 1.5rem .35rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem 1.5rem;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .88rem; font-weight: 500;
            transition: background .15s, color .15s;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-link.is-active { background: rgba(255,255,255,.12); color: #fff; border-left-color: #38bdf8; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.1);
            font-size: .78rem; color: rgba(255,255,255,.5);
        }
        .sidebar-footer form button {
            background: none; border: none; color: rgba(255,255,255,.6);
            cursor: pointer; font-size: .82rem; padding: 0;
        }
        .sidebar-footer form button:hover { color: #fff; }

        /* ── Main area ── */
        .admin-main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: .85rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .admin-breadcrumb { font-size: .82rem; color: #6c757d; }
        .admin-topbar-right { font-size: .82rem; color: #6c757d; }
        .admin-content { padding: 1.75rem; flex: 1; }

        /* ── Cards ── */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-weight: 700; font-size: .95rem; color: #0f2d4a; }
        .card-body { padding: 1.25rem; }

        /* ── Page header ── */
        .page-header { margin-bottom: 1.5rem; }
        .page-title { font-size: 1.35rem; font-weight: 800; color: #0f2d4a; }
        .page-sub { font-size: .85rem; color: #6c757d; margin-top: .2rem; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .55rem 1.1rem;
            border-radius: 8px;
            font-size: .85rem; font-weight: 600;
            border: none; cursor: pointer;
            text-decoration: none;
            transition: opacity .15s, box-shadow .15s;
        }
        .btn:hover { opacity: .88; }
        .btn-primary   { background: #0f2d4a; color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-ghost     { background: transparent; border: 1.5px solid #e5e7eb; color: #374151; }
        .btn-danger    { background: #fee2e2; color: #b91c1c; }
        .btn-sm        { padding: .35rem .75rem; font-size: .78rem; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .87rem; }
        thead th {
            background: #f8fafc;
            padding: .65rem 1rem;
            text-align: left;
            font-size: .75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: #6c757d;
            border-bottom: 1px solid #e5e7eb;
        }
        tbody td {
            padding: .8rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f8fafc; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: .22rem .65rem;
            border-radius: 999px;
            font-size: .72rem; font-weight: 700;
            text-transform: capitalize;
        }
        .badge-active, .badge-paid, .badge-confirmed, .badge-scheduled { background: #dcfce7; color: #15803d; }
        .badge-pending  { background: #fef9c3; color: #854d0e; }
        .badge-seasonal { background: #dbeafe; color: #1d4ed8; }
        .badge-inactive, .badge-cancelled, .badge-failed { background: #fee2e2; color: #b91c1c; }
        .badge-refunded { background: #f3f4f6; color: #6c757d; }
        .badge-boarded, .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-delayed  { background: #ffedd5; color: #c2410c; }
        .badge-completed { background: #ede9fe; color: #6d28d9; }
        .badge-regular  { background: #e0f2fe; color: #0369a1; }
        .badge-student  { background: #dcfce7; color: #15803d; }
        .badge-senior   { background: #fef9c3; color: #854d0e; }
        .badge-pwd      { background: #ede9fe; color: #6d28d9; }
        .badge-children { background: #fce7f3; color: #be185d; }

        /* ── Forms ── */
        .form-group { display: flex; flex-direction: column; gap: .4rem; }
        .form-label { font-size: .83rem; font-weight: 600; color: #374151; }
        .form-label .required { color: #ef4444; }
        .form-control {
            padding: .6rem .9rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: .88rem;
            font-family: inherit;
            color: #1f2937;
            background: #fff;
            transition: border-color .15s;
            width: 100%;
        }
        .form-control:focus { outline: none; border-color: #0f2d4a; }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: .78rem; color: #ef4444; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
        .form-full { grid-column: 1 / -1; }
        .form-actions { display: flex; gap: .75rem; padding-top: .5rem; }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex; flex-wrap: wrap; gap: .75rem;
            padding: 1rem 1.25rem;
            align-items: flex-start;
        }
        .filter-bar .form-group { min-width: 140px; }

        /* ── Pagination ── */
        .pagination-wrap { padding: 1rem 1.25rem; }
        .pagination-wrap nav { display: flex; gap: .4rem; }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
        }
        .stat-label { font-size: .75rem; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: .05em; }
        .stat-value { font-size: 1.65rem; font-weight: 800; color: #0f2d4a; margin-top: .2rem; line-height: 1; }
        .stat-sub   { font-size: .75rem; color: #9ca3af; margin-top: .3rem; }

        /* ── Alert ── */
        .alert {
            padding: .85rem 1.1rem;
            border-radius: 8px;
            font-size: .88rem;
            margin-bottom: 1rem;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            ⚓ Balanacan Port
            <span>Admin Panel</span>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-label">Main</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                📊 Dashboard
            </a>

            <div class="sidebar-label">Operations</div>
            <a href="{{ route('admin.routes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.routes.*') ? 'is-active' : '' }}">
                🗺 Routes
            </a>
            <a href="{{ route('admin.trips.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.trips.*') ? 'is-active' : '' }}">
                ⛴ Trips
            </a>
            <a href="{{ route('admin.bookings.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'is-active' : '' }}">
                🎫 Bookings
            </a>

            <div class="sidebar-label">Finance</div>
            <a href="{{ route('admin.fares.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.fares.*') ? 'is-active' : '' }}">
                💰 Fares
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}">
                📈 Reports
            </a>

            <div class="sidebar-label">Site</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                🌐 View Website
            </a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">🚪 Log Out</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="admin-main">
        <div class="admin-topbar">
            <div class="admin-breadcrumb">
                Admin / {!! strip_tags(View::yieldContent('breadcrumb')) !!}
            </div>
            <div class="admin-topbar-right">
                👤 {{ session('admin_email', 'Admin') }}
            </div>
        </div>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>