<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fare Guide | Balanacan Port e-Transact</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .fare-tabs { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:2rem; }
        .fare-tab {
            padding:.55rem 1.25rem;
            border:2px solid #e5e7eb; border-radius:999px;
            font-weight:700; font-size:.88rem;
            background:#fff; color:#6c757d;
            cursor:pointer; transition:all .2s;
        }
        .fare-tab:hover     { border-color:#0f2d4a; color:#0f2d4a; }
        .fare-tab.is-active { background:#0f2d4a; border-color:#0f2d4a; color:#fff; }
        .fare-panel { display:none; }
        .fare-panel.is-active { display:block; }

        .pfare-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(min(100%,180px),1fr));
            gap:1rem; margin-bottom:2.5rem;
        }
        .pfare-card {
            background:#fff; border:2px solid #e5e7eb;
            border-radius:16px; padding:1.5rem 1rem;
            text-align:center; transition:all .2s;
        }
        .pfare-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.1); border-color:#0f2d4a; transform:translateY(-3px); }
        .pfare-icon { font-size:2rem; margin-bottom:.5rem; }
        .pfare-label { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#6c757d; margin-bottom:.6rem; }
        .pfare-amount { font-size:2rem; font-weight:700; color:#0f2d4a; line-height:1; }
        .pfare-amount span { font-size:1.1rem; }
        .pfare-discount {
            display:inline-block; margin-top:.5rem;
            font-size:.72rem; font-weight:700;
            background:#dcfce7; color:#15803d;
            padding:.2rem .5rem; border-radius:999px;
        }
        .pfare-id { font-size:.75rem; color:#9ca3af; margin-top:.6rem; }

        .vfare-table-wrap { overflow-x:auto; margin-bottom:1.5rem; }
        .vfare-table { width:100%; border-collapse:collapse; font-size:.9rem; }
        .vfare-table th {
            background:#0f2d4a; color:#fff;
            padding:.75rem 1rem; text-align:left;
            font-size:.78rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.07em;
        }
        .vfare-table td { padding:.85rem 1rem; border-bottom:1px solid #f3f4f6; }
        .vfare-table tr:last-child td { border-bottom:none; }
        .vfare-table tr:nth-child(even) td { background:#f8fafc; }
        .vfare-table tr:hover td { background:#eff6ff; }
        .vfare-range { font-weight:700; color:#0f2d4a; }
        .vfare-size {
            display:inline-block; font-size:.72rem; font-weight:600;
            background:#f3f4f6; color:#6c757d;
            padding:.15rem .45rem; border-radius:6px; margin-left:.4rem;
        }
        .vfare-note { font-size:.75rem; color:#9ca3af; margin-top:.2rem; }

        .fare-disclaimer {
            background:#fffbeb; border:1px solid #fde68a;
            border-radius:10px; padding:.9rem 1.1rem;
            font-size:.85rem; color:#92400e;
        }

        .section { padding:4rem 1rem; }
        .container { max-width:1100px; margin:0 auto; }
        .section-hd { margin-bottom:2rem; }
        .section-hd.centered { text-align:center; }
        .eyebrow { font-size:.78rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#38bdf8; margin-bottom:.5rem; }
        .section-hd h2 { font-size:2rem; font-weight:800; color:#0f2d4a; }
        .section-desc { color:#6c757d; margin-top:.5rem; }

        @media(max-width:640px) { .pfare-grid { grid-template-columns:1fr 1fr; } }
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
                <a href="{{ route('fares') }}"     class="nav-link is-active" aria-current="page">Fare Guide</a>
            </nav>
            <a href="{{ route('book') }}" class="btn btn-nav-cta">🚢 Book a Trip</a>
        </div>
    </header>

    <main id="main-content">
        <section class="section" style="background:#f8fafc; padding-top:6rem;">
            <div class="container">
                <div class="section-hd centered">
                    <p class="eyebrow">PPA Official Fare Matrix</p>
                    <h2>Passenger &amp; Vehicle Fares</h2>
                    <p class="section-desc">Official fares set by the Philippine Ports Authority. Select a route below.</p>
                </div>

                {{-- Route tabs --}}
                <div class="fare-tabs" role="tablist">
                    @foreach($routes as $i => $route)
                    <button class="fare-tab {{ $i === 0 ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            data-tab="fare-panel-{{ $route->id }}">
                        {{ $route->name }}
                    </button>
                    @endforeach
                </div>

                @foreach($routes as $i => $route)
                <div class="fare-panel {{ $i === 0 ? 'is-active' : '' }}" id="fare-panel-{{ $route->id }}">

                    <h3 style="font-size:1rem;font-weight:800;color:#0f2d4a;margin-bottom:1rem;">🧍 Passenger Fares</h3>
                    <div class="pfare-grid">
                        @forelse($route->passengerFares->sortBy(fn($f) => match($f->fare_type){'regular'=>0,'student'=>1,'senior'=>2,'children'=>3,'pwd'=>4,default=>5}) as $fare)
                        <div class="pfare-card">
                            <div class="pfare-icon">{{ $fare->icon }}</div>
                            <div class="pfare-label">{{ $fare->label }}</div>
                            <div class="pfare-amount"><span>₱</span>{{ number_format($fare->amount, 2) }}</div>
                            @if($fare->discount_pct > 0)
                                <span class="pfare-discount">{{ number_format($fare->discount_pct, 0) }}% off</span>
                            @endif
                            <div class="pfare-id">🪪 {{ $fare->required_id }}</div>
                        </div>
                        @empty
                        <p style="color:#9ca3af;grid-column:1/-1;">No passenger fares set for this route.</p>
                        @endforelse
                    </div>

                    <h3 style="font-size:1rem;font-weight:800;color:#0f2d4a;margin-bottom:1rem;">🚗 Vehicle Fares</h3>
                    <div class="vfare-table-wrap">
                        <table class="vfare-table">
                            <thead>
                                <tr>
                                    <th>Vehicle Type</th>
                                    <th>Size</th>
                                    <th>Fare Range</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($route->vehicleFares as $vfare)
                                <tr>
                                    <td>{{ $vfare->icon }} {{ $vfare->label }}</td>
                                    <td>
                                        @if($vfare->size_description)
                                            <span class="vfare-size">{{ $vfare->size_description }}</span>
                                        @else —
                                        @endif
                                    </td>
                                    <td class="vfare-range">{{ $vfare->fare_range }}</td>
                                    <td><div class="vfare-note">{{ $vfare->notes ?? "Driver's fee included" }}</div></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:1.5rem;">No vehicle fares set for this route.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="fare-disclaimer">
                        <strong>📌 Note:</strong>
                        Driver's fee is included in all vehicle fares. Student, Senior Citizen (60+), and PWD discounts apply to passenger fares only — valid ID must be presented at checkout.
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-bottom" style="text-align:center;padding:1.5rem;">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.fare-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.fare-tab').forEach(t => { t.classList.remove('is-active'); t.setAttribute('aria-selected','false'); });
                document.querySelectorAll('.fare-panel').forEach(p => p.classList.remove('is-active'));
                tab.classList.add('is-active');
                tab.setAttribute('aria-selected','true');
                document.getElementById(tab.dataset.tab)?.classList.add('is-active');
            });
        });
    </script>
</body>
</html>