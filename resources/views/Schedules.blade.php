<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules | Balanacan Port e-Transact</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sched-tabs { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sched-tab {
            padding:.5rem 1.1rem; border:2px solid #e5e7eb; border-radius:999px;
            font-weight:700; font-size:.85rem; background:#fff; color:#6c757d;
            cursor:pointer; transition:all .2s;
        }
        .sched-tab:hover     { border-color:#0f2d4a; color:#0f2d4a; }
        .sched-tab.is-active { background:#0f2d4a; border-color:#0f2d4a; color:#fff; }
        .sched-panel { display:none; }
        .sched-panel.is-active { display:block; }

        .trip-grid { display:grid; gap:1rem; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); }
        .trip-card {
            background:#fff; border:2px solid #e5e7eb; border-radius:14px;
            padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.6rem;
            transition:all .2s;
        }
        .trip-card:hover { border-color:#0f2d4a; box-shadow:0 8px 24px rgba(0,0,0,.08); transform:translateY(-2px); }
        .trip-time { font-size:1.8rem; font-weight:800; color:#0f2d4a; line-height:1; }
        .trip-vessel { font-size:.85rem; color:#6c757d; font-weight:500; }
        .trip-slots { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.3rem; }
        .slot-badge {
            font-size:.72rem; font-weight:700; padding:.2rem .55rem;
            border-radius:6px; background:#f3f4f6; color:#374151;
        }
        .slot-badge.pax  { background:#dbeafe; color:#1d4ed8; }
        .slot-badge.veh  { background:#dcfce7; color:#15803d; }
        .status-chip {
            display:inline-block; font-size:.72rem; font-weight:700;
            padding:.2rem .6rem; border-radius:999px;
        }
        .status-chip.active    { background:#dcfce7; color:#15803d; }
        .status-chip.scheduled { background:#dbeafe; color:#1d4ed8; }
        .status-chip.canceled  { background:#fee2e2; color:#b91c1c; }
        .status-chip.inactive  { background:#f3f4f6; color:#6b7280; }

        .book-btn {
            margin-top:auto; padding:.55rem 1rem; background:#0f2d4a; color:#fff;
            border:none; border-radius:8px; font-weight:700; font-size:.85rem;
            cursor:pointer; text-align:center; text-decoration:none; display:block;
            transition:background .2s;
        }
        .book-btn:hover { background:#1a4a7a; }
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
                <a href="{{ route('schedules') }}" class="nav-link is-active" aria-current="page">Schedules</a>
                <a href="{{ route('bookings') }}"  class="nav-link">My Bookings</a>
                <a href="{{ route('fares') }}"     class="nav-link">Fare Guide</a>
            </nav>
            <a href="{{ route('book') }}" class="btn btn-nav-cta">🚢 Book a Trip</a>
        </div>
    </header>

    <main id="main-content" style="padding:6rem 1rem 4rem;">
        <div style="max-width:1100px;margin:0 auto;">
            <div style="margin-bottom:2rem;">
                <p style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#38bdf8;margin-bottom:.4rem;">Live Departure Board</p>
                <h1 style="font-size:2rem;font-weight:800;color:#0f2d4a;margin:0 0 .4rem;">🗓 Ferry Schedules</h1>
                <p style="color:#6c757d;">All available trips from Balanacan Port. Click a route tab to filter.</p>
            </div>

            <div class="sched-tabs" role="tablist">
                @foreach($routes as $i => $route)
                <button class="sched-tab {{ $i === 0 ? 'is-active' : '' }}"
                        role="tab"
                        data-tab="sched-panel-{{ $route->id }}">
                    {{ $route->name }}
                </button>
                @endforeach
            </div>

            @foreach($routes as $i => $route)
            <div class="sched-panel {{ $i === 0 ? 'is-active' : '' }}" id="sched-panel-{{ $route->id }}">
                @if($route->trips->isEmpty())
                    <div class="empty-state">
                        <div style="font-size:3rem;margin-bottom:1rem;">🚢</div>
                        <p style="font-weight:600;">No scheduled trips for this route right now.</p>
                    </div>
                @else
                <div class="trip-grid">
                    @foreach($route->trips as $trip)
                    <div class="trip-card">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                            <div class="trip-time">{{ substr($trip->departure_time, 0, 5) }}</div>
                            <span class="status-chip {{ $trip->status }}">{{ ucfirst($trip->status) }}</span>
                        </div>
                        <div class="trip-vessel">🚢 {{ $trip->vessel_name ?? 'Vessel TBA' }}</div>
                        <div class="trip-slots">
                            <span class="slot-badge pax">👤 {{ $trip->available_passenger_slots }} pax slots</span>
                            <span class="slot-badge veh">🚗 {{ $trip->available_vehicle_slots }} vehicle slots</span>
                        </div>
                        @if($trip->notes)
                            <div style="font-size:.8rem;color:#92400e;background:#fffbeb;padding:.4rem .7rem;border-radius:6px;">
                                📢 {{ $trip->notes }}
                            </div>
                        @endif
                        <a href="{{ route('book') }}?trip_id={{ $trip->id }}" class="book-btn">Book This Trip →</a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </main>

    <footer class="footer">
        <div class="footer-bottom" style="text-align:center;padding:1.5rem;">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.sched-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.sched-tab').forEach(t => t.classList.remove('is-active'));
                document.querySelectorAll('.sched-panel').forEach(p => p.classList.remove('is-active'));
                tab.classList.add('is-active');
                document.getElementById(tab.dataset.tab)?.classList.add('is-active');
            });
        });
    </script>
</body>
</html>