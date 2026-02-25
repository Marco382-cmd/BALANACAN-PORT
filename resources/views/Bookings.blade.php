<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Balanacan Port e-Transact</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background:#f8fafc; font-family:'Plus Jakarta Sans',sans-serif; }
        .wrap { max-width:760px; margin:0 auto; padding:6rem 1rem 4rem; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:2rem; margin-bottom:1.5rem; }
        .form-row { display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end; }
        .form-group { flex:1; min-width:200px; display:flex; flex-direction:column; gap:.35rem; }
        .form-label { font-size:.82rem; font-weight:700; color:#374151; }
        .form-control { padding:.65rem 1rem; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.9rem; font-family:inherit; }
        .form-control:focus { outline:none; border-color:#0f2d4a; }
        .btn-search { padding:.65rem 1.5rem; background:#0f2d4a; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; white-space:nowrap; }
        .btn-search:hover { background:#1a4a7a; }

        .alert-error { background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:.8rem 1rem; color:#b91c1c; font-size:.85rem; margin-bottom:1rem; }

        .bk-ref { font-size:1.4rem; font-weight:800; color:#0f2d4a; }
        .bk-meta { display:flex; gap:1rem; flex-wrap:wrap; margin:.5rem 0 1rem; }
        .bk-meta span { font-size:.82rem; color:#6b7280; }
        .bk-meta strong { color:#374151; }
        .badge { display:inline-block; font-size:.72rem; font-weight:700; padding:.2rem .6rem; border-radius:999px; }
        .badge-success   { background:#dcfce7; color:#15803d; }
        .badge-warning   { background:#fef9c3; color:#854d0e; }
        .badge-danger    { background:#fee2e2; color:#b91c1c; }
        .badge-secondary { background:#f3f4f6; color:#6b7280; }
        .badge-info      { background:#dbeafe; color:#1d4ed8; }

        table { width:100%; border-collapse:collapse; font-size:.88rem; }
        th { background:#f8fafc; padding:.6rem .9rem; text-align:left; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; }
        td { padding:.7rem .9rem; border-bottom:1px solid #f3f4f6; }
        tr:last-child td { border-bottom:none; }

        .empty-state { text-align:center; padding:3rem; color:#9ca3af; }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <header class="header" id="siteHeader">
        <div class="navbar">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">⚓</div>
                <div class="logo-text">
                    <span class="logo-name">Balanacan Port</span>
                    <span class="logo-sub">e-Transact</span>
                </div>
            </a>
            <nav class="nav-links">
                <a href="{{ route('home') }}"      class="nav-link">Home</a>
                <a href="{{ route('schedules') }}" class="nav-link">Schedules</a>
                <a href="{{ route('bookings') }}"  class="nav-link is-active" aria-current="page">My Bookings</a>
                <a href="{{ route('fares') }}"     class="nav-link">Fare Guide</a>
            </nav>
            <a href="{{ route('book') }}" class="btn btn-nav-cta">🚢 Book a Trip</a>
        </div>
    </header>

    <main id="main-content">
        <div class="wrap">
            <p style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#38bdf8;margin-bottom:.4rem;">Booking Lookup</p>
            <h1 style="font-size:2rem;font-weight:800;color:#0f2d4a;margin:0 0 .3rem;">🎫 My Bookings</h1>
            <p style="color:#6c757d;margin-bottom:1.5rem;">Enter your booking reference and email address to retrieve your ticket.</p>

            <div class="card">
                <form method="GET" action="{{ route('bookings') }}">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Booking Reference</label>
                            <input type="text" name="ref" class="form-control"
                                   placeholder="e.g. BK-A1B2C3D4"
                                   value="{{ request('ref') }}" style="text-transform:uppercase;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="your@email.com"
                                   value="{{ request('email') }}">
                        </div>
                        <button type="submit" class="btn-search">🔍 Find Booking</button>
                    </div>
                </form>
            </div>

            @if($error)
            <div class="alert-error">{{ $error }}</div>
            @endif

            @if($booking)
            {{-- ── Booking found ────────────────────────────────────── --}}
            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.5rem;margin-bottom:.5rem;">
                    <div class="bk-ref">{{ $booking->booking_reference }}</div>
                    <div style="display:flex;gap:.5rem;">
                        <span class="badge badge-{{ $booking->booking_status_badge }}">{{ ucfirst($booking->booking_status) }}</span>
                        <span class="badge badge-{{ $booking->payment_status_badge }}">{{ ucfirst($booking->payment_status) }}</span>
                    </div>
                </div>
                <div class="bk-meta">
                    <span>🗓 <strong>{{ $booking->travel_date->format('F j, Y') }}</strong></span>
                    <span>🚢 <strong>{{ $booking->trip->route->name }}</strong></span>
                    <span>⏰ <strong>{{ substr($booking->trip->departure_time, 0, 5) }}</strong></span>
                    <span>💳 <strong>{{ ucfirst($booking->payment_method ?? 'N/A') }}</strong></span>
                    <span>💰 <strong>₱{{ number_format($booking->total_amount, 2) }}</strong></span>
                </div>

                <h3 style="font-size:.85rem;font-weight:800;color:#0f2d4a;margin:1rem 0 .5rem;">🧍 Passengers</h3>
                <table>
                    <thead><tr><th>Name</th><th>Fare Type</th><th>ID</th><th>Amount</th></tr></thead>
                    <tbody>
                        @foreach($booking->passengers as $p)
                        <tr>
                            <td>{{ $p->full_name }}</td>
                            <td>{{ $p->fare_type_label }}</td>
                            <td>{{ $p->id_type ? $p->id_type.' — '.$p->id_number : '—' }}</td>
                            <td>₱{{ number_format($p->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($booking->vehicles->isNotEmpty())
                <h3 style="font-size:.85rem;font-weight:800;color:#0f2d4a;margin:1rem 0 .5rem;">🚗 Vehicles</h3>
                <table>
                    <thead><tr><th>Type</th><th>Plate</th><th>Description</th><th>Amount</th></tr></thead>
                    <tbody>
                        @foreach($booking->vehicles as $v)
                        <tr>
                            <td>{{ $v->vehicleFare->label ?? '—' }}</td>
                            <td>{{ $v->plate_number }}</td>
                            <td>{{ $v->vehicle_description ?? '—' }}</td>
                            <td>₱{{ number_format($v->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid #f3f4f6;font-size:.82rem;color:#6b7280;">
                    Booked by <strong>{{ $booking->contact_name }}</strong> · {{ $booking->contact_email }} · {{ $booking->contact_phone }}
                </div>
            </div>
            @elseif(request()->hasAny(['ref','email']))
            {{-- No result yet but form was submitted --}}
            @else
            <div class="empty-state">
                <div style="font-size:3rem;margin-bottom:1rem;">🎫</div>
                <p style="font-weight:600;">Enter your booking reference and email above to retrieve your ticket.</p>
            </div>
            @endif
        </div>
    </main>

    <footer class="footer">
        <div class="footer-bottom" style="text-align:center;padding:1.5rem;">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>