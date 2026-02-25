<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | Balanacan Port e-Transact</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background:#f8fafc; font-family:'Plus Jakarta Sans',sans-serif; }
        .wrap { max-width:680px; margin:0 auto; padding:6rem 1rem 4rem; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:2rem; margin-bottom:1.5rem; }
        .success-banner { background:linear-gradient(135deg,#0f2d4a,#1a4a7a); color:#fff; border-radius:16px; padding:2.5rem; text-align:center; margin-bottom:1.5rem; }
        .success-icon { font-size:3.5rem; margin-bottom:.75rem; }
        .success-ref { font-size:1.8rem; font-weight:800; letter-spacing:.05em; background:rgba(255,255,255,.15); border-radius:10px; padding:.5rem 1.25rem; display:inline-block; margin:.75rem 0; }
        .badge { display:inline-block; font-size:.72rem; font-weight:700; padding:.2rem .6rem; border-radius:999px; }
        .badge-warning { background:#fef9c3; color:#854d0e; }
        .badge-success { background:#dcfce7; color:#15803d; }
        table { width:100%; border-collapse:collapse; font-size:.88rem; }
        th { background:#f8fafc; padding:.6rem .9rem; text-align:left; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6b7280; }
        td { padding:.7rem .9rem; border-bottom:1px solid #f3f4f6; }
        tr:last-child td { border-bottom:none; }
        .info-row { display:flex; gap:.5rem; flex-wrap:wrap; margin:.5rem 0; }
        .info-pill { font-size:.82rem; background:#f3f4f6; border-radius:6px; padding:.3rem .7rem; color:#374151; }
        .info-pill strong { color:#0f2d4a; }
        .btn-primary { display:inline-block; padding:.7rem 1.5rem; background:#0f2d4a; color:#fff; border-radius:8px; font-weight:700; text-decoration:none; }
        .btn-secondary { display:inline-block; padding:.7rem 1.5rem; background:#fff; color:#0f2d4a; border:2px solid #0f2d4a; border-radius:8px; font-weight:700; text-decoration:none; }
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
                <a href="{{ route('bookings') }}"  class="nav-link">My Bookings</a>
                <a href="{{ route('fares') }}"     class="nav-link">Fare Guide</a>
            </nav>
            <a href="{{ route('book') }}" class="btn btn-nav-cta">🚢 Book a Trip</a>
        </div>
    </header>

    <main id="main-content">
        <div class="wrap">
            <div class="success-banner">
                <div class="success-icon">✅</div>
                <h1 style="font-size:1.5rem;font-weight:800;margin:0;">Booking Received!</h1>
                <p style="opacity:.75;margin:.4rem 0 .75rem;">Your reference number is:</p>
                <div class="success-ref">{{ $booking->booking_reference }}</div>
                <p style="opacity:.65;font-size:.85rem;margin:.75rem 0 0;">Save this reference number to look up your booking anytime.</p>
            </div>

            <div class="card">
                <p style="font-size:.85rem;font-weight:800;color:#0f2d4a;margin-bottom:.75rem;">📋 Booking Summary</p>
                <div class="info-row">
                    <span class="info-pill">🗓 <strong>{{ $booking->travel_date->format('F j, Y') }}</strong></span>
                    <span class="info-pill">🚢 <strong>{{ $booking->trip->route->name }}</strong></span>
                    <span class="info-pill">⏰ <strong>{{ substr($booking->trip->departure_time, 0, 5) }}</strong></span>
                    <span class="info-pill">💳 <strong>{{ ucfirst($booking->payment_method ?? 'N/A') }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="badge badge-warning">Payment: {{ ucfirst($booking->payment_status) }}</span>
                    <span class="badge badge-warning">Status: {{ ucfirst($booking->booking_status) }}</span>
                </div>
            </div>

            <div class="card">
                <p style="font-size:.85rem;font-weight:800;color:#0f2d4a;margin-bottom:.75rem;">🧍 Passengers</p>
                <table>
                    <thead><tr><th>Name</th><th>Fare Type</th><th>Amount</th></tr></thead>
                    <tbody>
                        @foreach($booking->passengers as $p)
                        <tr>
                            <td>{{ $p->full_name }}</td>
                            <td>{{ $p->fare_type_label }}</td>
                            <td>₱{{ number_format($p->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($booking->vehicles->isNotEmpty())
                <p style="font-size:.85rem;font-weight:800;color:#0f2d4a;margin:1.25rem 0 .75rem;">🚗 Vehicles</p>
                <table>
                    <thead><tr><th>Type</th><th>Plate</th><th>Amount</th></tr></thead>
                    <tbody>
                        @foreach($booking->vehicles as $v)
                        <tr>
                            <td>{{ $v->vehicleFare->label ?? '—' }}</td>
                            <td>{{ $v->plate_number }}</td>
                            <td>₱{{ number_format($v->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:800;color:#0f2d4a;">Total Amount</span>
                    <span style="font-size:1.3rem;font-weight:800;color:#0f2d4a;">₱{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:1rem;margin-bottom:1.5rem;font-size:.85rem;color:#92400e;">
                <strong>📌 Important:</strong> Please present this reference number at the port. Payment is due upon boarding. Discounted passengers must show valid ID.
            </div>

            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <a href="{{ route('bookings') }}?ref={{ $booking->booking_reference }}&email={{ $booking->contact_email }}" class="btn-primary">
                    🎫 View My Booking
                </a>
                <a href="{{ route('book') }}" class="btn-secondary">+ Book Another Trip</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-bottom" style="text-align:center;padding:1.5rem;">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>