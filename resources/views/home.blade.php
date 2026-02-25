<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Balanacan Port e-Transact — Book ferry tickets online to and from Marinduque. Fast, cashless, hassle-free.">
    <title>Balanacan Port e-Transact | Home</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <style>
        /* ── Smooth scroll ── */
        html { scroll-behavior: smooth; }

        /* ── Fare Matrix Styles ── */
        .fare-section { background: var(--bg); }

        .fare-tabs {
            display: flex; gap: 0.5rem; flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .fare-tab {
            padding: 0.55rem 1.25rem;
            border: 2px solid var(--g200);
            border-radius: var(--r-full);
            font-weight: 700; font-size: 0.88rem;
            background: var(--white); color: var(--g600);
            cursor: pointer; transition: all 0.2s;
        }
        .fare-tab:hover      { border-color: var(--ocean); color: var(--ocean); }
        .fare-tab.is-active  { background: var(--ocean); border-color: var(--ocean); color: var(--white); }

        .fare-panel { display: none; }
        .fare-panel.is-active { display: block; }

        /* Passenger fare cards */
        .pfare-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 180px), 1fr));
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .pfare-card {
            background: var(--white);
            border: 2px solid var(--g200);
            border-radius: var(--r-xl);
            padding: 1.5rem 1rem;
            text-align: center;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
            position: relative;
        }
        .pfare-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--ocean);
            transform: translateY(-3px);
        }
        .pfare-card--regular { border-color: var(--ocean); }

        .pfare-icon { font-size: 2rem; margin-bottom: 0.5rem; line-height: 1; }

        .pfare-label {
            font-size: 0.75rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--g500); margin-bottom: 0.6rem;
        }
        .pfare-amount {
            font-family: var(--font-display);
            font-size: 2rem; font-weight: 600;
            color: var(--navy); line-height: 1;
        }
        .pfare-amount span { font-size: 1.1rem; }

        .pfare-discount {
            display: inline-block;
            margin-top: 0.5rem;
            font-size: 0.72rem; font-weight: 700;
            background: #dcfce7; color: #15803d;
            padding: 0.2rem 0.5rem; border-radius: var(--r-full);
        }
        .pfare-id {
            font-size: 0.75rem; color: var(--g400);
            margin-top: 0.6rem; line-height: 1.4;
        }
        .pfare-note {
            font-size: 0.8rem; color: var(--g500);
            margin-top: 0.35rem;
        }

        /* Vehicle fare table */
        .vfare-table-wrap { overflow-x: auto; margin-bottom: 1.5rem; }

        .vfare-table {
            width: 100%; border-collapse: collapse;
            font-size: 0.9rem;
        }
        .vfare-table th {
            background: var(--navy); color: var(--white);
            padding: 0.75rem 1rem; text-align: left;
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
        }
        .vfare-table th:first-child { border-radius: var(--r-sm) 0 0 0; }
        .vfare-table th:last-child  { border-radius: 0 var(--r-sm) 0 0; }

        .vfare-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--g200);
            color: var(--g800);
        }
        .vfare-table tr:last-child td { border-bottom: none; }
        .vfare-table tr:nth-child(even) td { background: var(--g50); }
        .vfare-table tr:hover td { background: var(--sky-soft); }

        .vfare-icon { font-size: 1.2rem; margin-right: 0.4rem; }
        .vfare-size {
            display: inline-block;
            font-size: 0.72rem; font-weight: 600;
            background: var(--g100); color: var(--g600);
            padding: 0.15rem 0.45rem; border-radius: var(--r-sm);
            margin-left: 0.4rem;
        }
        .vfare-range { font-weight: 700; color: var(--navy); }
        .vfare-note  { font-size: 0.75rem; color: var(--g400); margin-top: 0.25rem; }

        .fare-disclaimer {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: var(--r-md); padding: 0.9rem 1.1rem;
            font-size: 0.85rem; color: #92400e;
        }
        .fare-disclaimer strong { color: #78350f; }

        /* Highlighted regular fare badge on card */
        .pfare-card--regular .pfare-label { color: var(--ocean); }

        @media (max-width: 640px) {
            .pfare-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 380px) {
            .pfare-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    {{-- Skip link --}}
    <a href="#main-content" class="skip-link">Skip to main content</a>

    {{-- ================================================
         HEADER
    ================================================ --}}
    <header class="header" id="siteHeader">
        <div class="navbar">
            <a href="{{ route('home') }}" class="logo" aria-label="Balanacan Port e-Transact Home">
                <div class="logo-icon">⚓</div>
                <div class="logo-text">
                    <span class="logo-name">Balanacan Port</span>
                    <span class="logo-sub">e-Transact</span>
                </div>
            </a>

            <nav class="nav-links" aria-label="Primary">
                <a href="{{ route('home') }}"      class="nav-link is-active" aria-current="page">Home</a>
                <a href="{{ route('schedules') }}" class="nav-link">Schedules</a>
                <a href="{{ route('bookings') }}"  class="nav-link">My Bookings</a>
                <a href="{{ route('home') }}#fare-matrix" class="nav-link">Fare Guide</a>
            </nav>

            <a href="{{ route('book') }}" class="btn btn-nav-cta">🚢 Book a Trip</a>

            <button class="hamburger" id="hamburger"
                    aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
            <nav aria-label="Mobile">
                <a href="{{ route('home') }}"      class="mobile-link is-active">Home</a>
                <a href="{{ route('schedules') }}" class="mobile-link">Schedules</a>
                <a href="{{ route('bookings') }}"  class="mobile-link">My Bookings</a>
                <a href="{{ route('home') }}#fare-matrix" class="mobile-link">Fare Guide</a>
            </nav>
            <a href="{{ route('book') }}" class="btn btn-primary w-full mt-1">🚢 Book a Trip Now</a>
        </div>
    </header>

    <main id="main-content">

        {{-- ================================================
             HERO
        ================================================ --}}
        <section class="hero">
            <div class="hero-inner">

                <div class="hero-copy">
                    <div class="hero-badge">
                        <span class="badge-pulse"></span>
                        Online booking now available
                    </div>

                    <h1 class="hero-title">
                        Your ferry to<br>
                        <em>Marinduque</em><br>
                        booked in minutes.
                    </h1>
                    <p class="hero-sub">
                        Skip the queue. Book online, pay cashlessly, and board with
                        a QR code — from anywhere, 24/7.
                    </p>

                    {{-- Quick Search Widget --}}
                    <div class="search-widget">
                        <h2 class="search-widget-title">Find a Trip</h2>
                        <div class="search-fields">
                            <div class="search-field">
                                <label for="qRoute">Route</label>
                                <select id="qRoute">
                                    <option value="" disabled selected>Select route…</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="search-field">
                                <label for="qDate">Date</label>
                                <input type="date" id="qDate" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="search-field search-field--sm">
                                <label for="qPax">Passengers</label>
                                <input type="number" id="qPax" value="1" min="1" max="50">
                            </div>
                        </div>
                        <a href="{{ route('schedules') }}" class="btn btn-search" id="searchBtn">
                            Search Trips →
                        </a>
                    </div>

                    <div class="trust-row">
                        <span>✅ Secure payment</span>
                        <span>⚡ Instant e-ticket</span>
                        <span>🎫 Discounted fares available</span>
                    </div>
                </div>

                {{-- Right: Live departure card + stats --}}
                <div class="hero-panel">
                    <div class="departure-card">
                        <div class="dc-header">
                            <span class="dc-live-dot"></span>
                            <span>Next Departure</span>
                        </div>
                        @if($nextDeparture)
                        <div class="dc-route">
                            <div class="dc-port">
                                <div class="dc-code">{{ $nextDeparture->route->origin_code }}</div>
                                <div class="dc-name">{{ $nextDeparture->route->origin_name }}</div>
                            </div>
                            <div class="dc-ship" aria-hidden="true">⛴</div>
                            <div class="dc-port dc-port--right">
                                <div class="dc-code">{{ $nextDeparture->route->destination_code }}</div>
                                <div class="dc-name">{{ $nextDeparture->route->destination_name }}</div>
                            </div>
                        </div>
                        <div class="dc-meta">
                            <div class="dc-meta-item">
                                <div class="dc-meta-label">Departs</div>
                                <div class="dc-meta-val">{{ $nextDeparture->departure_formatted }}</div>
                            </div>
                            <div class="dc-meta-item">
                                <div class="dc-meta-label">Duration</div>
                                <div class="dc-meta-val">{{ $nextDeparture->route->duration_label }}</div>
                            </div>
                            <div class="dc-meta-item">
                                <div class="dc-meta-label">From</div>
                                <div class="dc-meta-val">
                                    ₱{{ number_format($nextDeparture->route->base_passenger_fare, 0) }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="dc-route" style="justify-content:center;padding:1rem 0;">
                            <p style="color:rgba(255,255,255,0.6);font-size:0.9rem;">No more departures today.</p>
                        </div>
                        @endif
                        <a href="{{ route('book') }}" class="dc-book-btn">Book this trip</a>
                    </div>

                    <div class="hero-stats-row">
                        <div class="hstat">
                            <div class="hstat-num">10+</div>
                            <div class="hstat-lbl">Daily trips</div>
                        </div>
                        <div class="hstat">
                            <div class="hstat-num">{{ $routes->count() }}</div>
                            <div class="hstat-lbl">Routes</div>
                        </div>
                        <div class="hstat">
                            <div class="hstat-num">24/7</div>
                            <div class="hstat-lbl">Booking</div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ================================================
             HOW IT WORKS
        ================================================ --}}
        <section class="section how-section">
            <div class="container">
                <div class="section-hd centered">
                    <p class="eyebrow">Simple Process</p>
                    <h2>Book in 3 Easy Steps</h2>
                </div>
                <ol class="steps-row">
                    <li class="step-card">
                        <div class="step-num">1</div>
                        <div class="step-emoji">🗺</div>
                        <h3>Choose Route & Date</h3>
                        <p>Pick your ferry route, travel date, and how many passengers. Filter by time or vessel type.</p>
                    </li>
                    <li class="step-card">
                        <div class="step-num">2</div>
                        <div class="step-emoji">💳</div>
                        <h3>Fill Details & Pay</h3>
                        <p>Enter passenger information, select your fare type, add vehicles if needed, then pay via GCash, Maya, or card.</p>
                    </li>
                    <li class="step-card">
                        <div class="step-num">3</div>
                        <div class="step-emoji">📲</div>
                        <h3>Scan & Board</h3>
                        <p>Your QR ticket is sent instantly to your SMS and email. Just scan at the port — done!</p>
                    </li>
                </ol>
                <div class="centered-action">
                    <a href="{{ route('book') }}" class="btn btn-primary">Start Booking Now →</a>
                </div>
            </div>
        </section>

        {{-- ================================================
             ROUTES
        ================================================ --}}
        <section class="section routes-section">
            <div class="container">
                <div class="section-hd">
                    <p class="eyebrow">Available Routes</p>
                    <h2>Where Can You Go?</h2>
                    <p class="section-desc">Daily ferry services between Marinduque and Quezon Province. Tap a route to book.</p>
                </div>

                <div class="routes-grid">
                    @foreach($routes as $route)
                    <article class="route-card {{ $route->status === 'seasonal' ? 'route-card--seasonal' : '' }}">
                        <div class="route-card-top">
                            <span class="route-badge route-badge--{{ $route->status }}">
                                ● {{ ucfirst($route->status) }}
                            </span>
                            <span class="route-freq">{{ $route->trips_per_day }}× daily</span>
                        </div>
                        <div class="route-journey">
                            <div class="rj-port">
                                <div class="rj-code">{{ $route->origin_code }}</div>
                                <div class="rj-name">{{ $route->origin_name }}</div>
                                <div class="rj-loc">{{ $route->origin_location }}</div>
                            </div>
                            <div class="rj-arrow">⇄</div>
                            <div class="rj-port rj-port--r">
                                <div class="rj-code">{{ $route->destination_code }}</div>
                                <div class="rj-name">{{ $route->destination_name }}</div>
                                <div class="rj-loc">{{ $route->destination_location }}</div>
                            </div>
                        </div>
                        <dl class="route-info">
                            <div>
                                <dt>Duration</dt>
                                <dd>{{ $route->duration_label }}</dd>
                            </div>
                            <div>
                                <dt>Adult Fare</dt>
                                <dd>₱{{ number_format($route->passengerFares->where('fare_type','regular')->first()?->amount ?? 0, 0) }}</dd>
                            </div>
                            <div>
                                <dt>Vehicle</dt>
                                <dd>From ₱{{ number_format($route->vehicleFares->min('fare_min') ?? 0, 0) }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('book', ['route' => $route->id]) }}"
                           class="btn btn-route {{ $route->status === 'seasonal' ? 'btn-route--ghost' : '' }}">
                            {{ $route->status === 'seasonal' ? 'Check Schedule' : 'Book This Route' }}
                        </a>
                    </article>
                    @endforeach
                </div>

                <p class="routes-disclaimer">
                    ℹ️ Fares shown are base adult fares per the PPA Fare Matrix.
                    Students, senior citizens (60+), and PWDs get discounted rates —
                    <a href="#fare-matrix" style="color:var(--ocean);font-weight:600;">see fare matrix below</a>.
                </p>
            </div>
        </section>

        {{-- ================================================
             FARE MATRIX
        ================================================ --}}
        <section class="section fare-section" id="fare-matrix">
            <div class="container">
                <div class="section-hd centered">
                    <p class="eyebrow">PPA Official Fare Matrix</p>
                    <h2>Passenger & Vehicle Fares</h2>
                    <p class="section-desc" style="margin:0 auto 1.5rem;">
                        Official fares set by the Philippine Ports Authority.
                        Select a route below to view its full fare breakdown.
                    </p>
                </div>

                {{-- Route tabs --}}
                <div class="fare-tabs" role="tablist" aria-label="Select route fares">
                    @foreach($routes as $i => $route)
                    <button class="fare-tab {{ $i === 0 ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            aria-controls="fare-panel-{{ $route->id }}"
                            data-tab="fare-panel-{{ $route->id }}">
                        {{ $route->name }}
                    </button>
                    @endforeach
                </div>

                {{-- Per-route panels --}}
                @foreach($routes as $i => $route)
                <div class="fare-panel {{ $i === 0 ? 'is-active' : '' }}"
                     id="fare-panel-{{ $route->id }}"
                     role="tabpanel">

                    {{-- ── Passenger Fares ── --}}
                    <h3 style="font-size:1rem;font-weight:800;color:var(--navy);margin-bottom:1rem;">
                        🧍 Passenger Fares
                    </h3>

                    <div class="pfare-grid">
                        @foreach($route->passengerFares->sortBy(fn($f) => match($f->fare_type){
                            'regular'=>0,'student'=>1,'senior'=>2,'children'=>3,'pwd'=>4,default=>5
                        }) as $fare)
                        <div class="pfare-card pfare-card--{{ $fare->fare_type }}">
                            <div class="pfare-icon">{{ $fare->icon }}</div>
                            <div class="pfare-label">{{ $fare->label }}</div>
                            <div class="pfare-amount">
                                <span>₱</span>{{ number_format($fare->amount, 2) }}
                            </div>
                            @if($fare->discount_pct > 0)
                                <span class="pfare-discount">
                                    {{ number_format($fare->discount_pct, 0) }}% off
                                </span>
                            @endif
                            <div class="pfare-id">
                                🪪 {{ $fare->required_id }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- ── Vehicle Fares ── --}}
                    <h3 style="font-size:1rem;font-weight:800;color:var(--navy);margin-bottom:1rem;">
                        🚗 Vehicle Fares (Full Rate)
                    </h3>

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
                                @foreach($route->vehicleFares as $vfare)
                                <tr>
                                    <td>
                                        <span class="vfare-icon">{{ $vfare->icon }}</span>
                                        {{ $vfare->label }}
                                    </td>
                                    <td>
                                        @if($vfare->size_description)
                                            <span class="vfare-size">{{ $vfare->size_description }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="vfare-range">{{ $vfare->fare_range }}</td>
                                    <td>
                                        <div class="vfare-note">{{ $vfare->notes ?? "Driver's fee included" }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="fare-disclaimer">
                        <strong>📌 Note:</strong>
                        Driver's fee is included in all vehicle fares.
                        Shipping lines may give discounts at their discretion.
                        Student, Senior Citizen, and PWD discounts apply to passenger fares only — valid ID must be presented at checkout.
                    </div>
                </div>
                @endforeach

            </div>
        </section>

        {{-- ================================================
             FEATURES
        ================================================ --}}
        <section class="section features-section">
            <div class="container">
                <div class="section-hd centered">
                    <p class="eyebrow">Why e-Transact?</p>
                    <h2>Everything You Need, In One Place</h2>
                </div>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--blue">📱</div>
                        <div class="fi-body">
                            <h3>Book from Anywhere</h3>
                            <p>No trip to the port needed. Book on your phone or laptop, 24 hours a day, 7 days a week.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--green">💳</div>
                        <div class="fi-body">
                            <h3>Cashless & Secure</h3>
                            <p>Pay via GCash, Maya, or card. All transactions are fully encrypted and safe.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--amber">🚗</div>
                        <div class="fi-body">
                            <h3>Vehicle Reservations</h3>
                            <p>Reserve space for bicycles, motorcycles, cars, vans, or trucks. Driver's fee included!</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--purple">💰</div>
                        <div class="fi-body">
                            <h3>Discounted Fares</h3>
                            <p>Students, seniors, and PWDs enjoy reduced fares per PPA rates — just show your valid ID.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--teal">🔔</div>
                        <div class="fi-body">
                            <h3>Real-Time Alerts</h3>
                            <p>SMS and email alerts for trip delays, cancellations, and port announcements.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="fi-icon fi-icon--red">🎫</div>
                        <div class="fi-body">
                            <h3>Instant QR Ticket</h3>
                            <p>Get your digital ticket the moment you pay. Scan at the port — no printing needed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================================================
             DISCOUNT SECTION
        ================================================ --}}
        <section class="discount-section">
            <div class="container">
                <div class="discount-inner">
                    <div class="discount-circle" aria-hidden="true">
                        <span class="dc-pct">20%</span>
                        <span class="dc-off">OFF</span>
                    </div>
                    <div class="discount-body">
                        <p class="eyebrow">Special Fares</p>
                        <h2>Are You Eligible for a Discount?</h2>
                        <p>Students, senior citizens aged 60+, and persons with disabilities (PWD) are entitled to discounted fares on all passenger trips under Philippine law.</p>
                        <ul class="discount-list">
                            <li>🎓 <strong>Students</strong> — ₱400 (Balanacan–Lucena) | valid school ID required</li>
                            <li>👴 <strong>Senior Citizens (60+)</strong> — ₱335 (Balanacan–Lucena) | Senior Citizen ID required</li>
                            <li>♿ <strong>PWD</strong> — ₱376 (Balanacan–Lucena) | PWD ID required</li>
                            <li>👦 <strong>Children</strong> — ₱235 (Balanacan–Lucena)</li>
                        </ul>
                        <a href="{{ route('book') }}" class="btn btn-primary">Book with Discount</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================================================
             CTA BANNER
        ================================================ --}}
        <section class="cta-section">
            <div class="container">
                <div class="cta-inner">
                    <div class="cta-ship" aria-hidden="true">⛴</div>
                    <h2 class="cta-title">Ready to Set Sail?</h2>
                    <p class="cta-desc">Book your ferry ticket in under 2 minutes. Available 24/7, no account needed.</p>
                    <div class="cta-btns">
                        <a href="{{ route('book') }}"      class="btn btn-cta-main">🚢 Book a Trip Now</a>
                        <a href="{{ route('schedules') }}" class="btn btn-cta-ghost">View All Schedules</a>
                    </div>
                    <p class="cta-note">🔒 Secured with 256-bit encryption</p>
                </div>
            </div>
        </section>

    </main>

    {{-- ================================================
         FOOTER
    ================================================ --}}
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="logo logo--footer">
                    <div class="logo-icon logo-icon--sm">⚓</div>
                    <div class="logo-text">
                        <span class="logo-name">Balanacan Port</span>
                        <span class="logo-sub">e-Transact</span>
                    </div>
                </a>
                <p class="footer-tagline">The official online ferry booking platform for Balanacan Port, Mogpog, Marinduque.</p>
                <div class="footer-payments">
                    <span>GCash</span>
                    <span>Maya</span>
                    <span>Visa</span>
                    <span>Mastercard</span>
                </div>
            </div>

            <div class="footer-col">
                <h3>Book & Travel</h3>
                <ul>
                    <li><a href="{{ route('book') }}">Book a Trip</a></li>
                    <li><a href="{{ route('schedules') }}">View Schedules</a></li>
                    <li><a href="{{ route('bookings') }}">My Bookings</a></li>
                    <li><a href="{{ route('home') }}#fare-matrix">Fare Guide</a></li>
                    <li><a href="#">Travel Advisories</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Help & Support</h3>
                <ul>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Cancellation Policy</a></li>
                    <li><a href="#">Refund Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Contact Us</h3>
                <address>
                    <p><strong>Email</strong><a href="mailto:info@balanacanport.gov.ph">info@balanacanport.gov.ph</a></p>
                    <p><strong>Phone</strong><a href="tel:+639171234567">+63-917-123-4567</a></p>
                    <p><strong>Address</strong>Balanacan, Mogpog, Marinduque</p>
                </address>
                <div class="footer-hours">
                    <strong>Port Office Hours</strong>
                    <span>Mon – Sun &nbsp;|&nbsp; 5:00 AM – 8:00 PM</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Accessibility</a>
            </div>
        </div>
    </footer>

<script>
    // ── Fare tabs ────────────────────────────────────────
    document.querySelectorAll('.fare-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.fare-tab').forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected','false');
            });
            document.querySelectorAll('.fare-panel').forEach(p => p.classList.remove('is-active'));

            tab.classList.add('is-active');
            tab.setAttribute('aria-selected','true');
            document.getElementById(tab.dataset.tab)?.classList.add('is-active');
        });
    });

    // ── Sticky header shadow ─────────────────────────────
    const header = document.getElementById('siteHeader');
    if (header) {
        window.addEventListener('scroll', () => {
            header.style.boxShadow = window.scrollY > 10
                ? '0 4px 20px rgba(0,0,0,0.10)'
                : '0 1px 3px rgba(0,0,0,0.07)';
        }, { passive: true });
    }

    // ── Search button: attach params to URL ──────────────
    document.getElementById('searchBtn')?.addEventListener('click', function(e) {
        const route = document.getElementById('qRoute').value;
        const date  = document.getElementById('qDate').value;
        const pax   = document.getElementById('qPax').value;
        if (!route || !date) {
            e.preventDefault();
            alert('Please select a route and travel date.');
            return;
        }
        const url = new URL(this.href, window.location.origin);
        url.searchParams.set('route', route);
        url.searchParams.set('date', date);
        url.searchParams.set('pax', pax);
        this.href = url.toString();
    });

    // ── Auto-scroll to fare matrix if arriving via #fare-matrix hash ──
    if (window.location.hash === '#fare-matrix') {
        document.getElementById('fare-matrix')?.scrollIntoView({ behavior: 'smooth' });
    }
</script>

</body>
</html> 