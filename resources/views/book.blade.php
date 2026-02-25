<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Trip | Balanacan Port e-Transact</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background:#f8fafc; font-family:'Plus Jakarta Sans',sans-serif; }
        .bk-wrap { max-width:780px; margin:0 auto; padding:6rem 1rem 4rem; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:2rem; margin-bottom:1.5rem; }
        .card-title { font-size:1rem; font-weight:800; color:#0f2d4a; margin-bottom:1.25rem; padding-bottom:.75rem; border-bottom:1px solid #f3f4f6; }
        .form-row { display:grid; gap:1rem; grid-template-columns:1fr 1fr; margin-bottom:1rem; }
        .form-row.single { grid-template-columns:1fr; }
        .form-row.triple { grid-template-columns:1fr 1fr 1fr; }
        @media(max-width:560px) { .form-row, .form-row.triple { grid-template-columns:1fr; } }
        .form-group { display:flex; flex-direction:column; gap:.35rem; }
        .form-label { font-size:.82rem; font-weight:700; color:#374151; }
        .form-label .req { color:#ef4444; }
        .form-control {
            padding:.6rem .9rem; border:1.5px solid #e5e7eb; border-radius:8px;
            font-size:.9rem; font-family:inherit; transition:border-color .2s;
            background:#fff;
        }
        .form-control:focus { outline:none; border-color:#0f2d4a; }
        .form-control.is-invalid { border-color:#ef4444; }
        .invalid-feedback { font-size:.78rem; color:#ef4444; margin-top:.2rem; }

        .pax-block {
            border:1.5px solid #e5e7eb; border-radius:10px; padding:1rem;
            margin-bottom:.75rem; position:relative;
        }
        .pax-block .pax-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:.75rem; }
        .pax-block .pax-num { font-size:.8rem; font-weight:800; color:#0f2d4a; text-transform:uppercase; letter-spacing:.05em; }
        .btn-remove { background:#fee2e2; color:#b91c1c; border:none; border-radius:6px; padding:.25rem .6rem; font-size:.78rem; font-weight:700; cursor:pointer; }
        .btn-remove:hover { background:#fca5a5; }

        .btn-add { background:#f0f9ff; color:#0369a1; border:1.5px dashed #7dd3fc; border-radius:8px; padding:.6rem 1rem; font-size:.85rem; font-weight:700; cursor:pointer; width:100%; margin-top:.25rem; transition:all .2s; }
        .btn-add:hover { background:#e0f2fe; border-color:#38bdf8; }

        .total-box { background:#0f2d4a; color:#fff; border-radius:12px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .total-label { font-size:.85rem; opacity:.7; }
        .total-amount { font-size:1.8rem; font-weight:800; }

        .btn-submit { width:100%; padding:.9rem; background:#0f2d4a; color:#fff; border:none; border-radius:10px; font-size:1rem; font-weight:800; cursor:pointer; transition:background .2s; }
        .btn-submit:hover { background:#1a4a7a; }

        .fare-hint { font-size:.75rem; color:#6b7280; margin-top:.2rem; }
        .section-note { font-size:.82rem; color:#6b7280; margin-bottom:1rem; }

        .alert-error { background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:.9rem 1rem; color:#b91c1c; font-size:.85rem; margin-bottom:1rem; }
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
        <div class="bk-wrap">
            <p style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#38bdf8;margin-bottom:.4rem;">Online Reservation</p>
            <h1 style="font-size:2rem;font-weight:800;color:#0f2d4a;margin:0 0 .3rem;">🚢 Book a Trip</h1>
            <p style="color:#6c757d;margin-bottom:1.5rem;">Fill in the form below to reserve your ferry ticket.</p>

            @if($errors->any())
            <div class="alert-error">
                <strong>Please fix the following:</strong>
                <ul style="margin:.4rem 0 0 1rem;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('book.store') }}" id="bookingForm">
                @csrf

                {{-- ── Trip Selection ──────────────────────────────────── --}}
                <div class="card">
                    <div class="card-title">🗓 Trip Details</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Route <span class="req">*</span></label>
                            <select id="routeSelect" class="form-control">
                                <option value="">Select a route…</option>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Trip / Departure Time <span class="req">*</span></label>
                            <select name="trip_id" id="tripSelect" class="form-control {{ $errors->has('trip_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Select route first…</option>
                                @foreach($trips as $trip)
                                <option value="{{ $trip->id }}"
                                    data-route="{{ $trip->route_id }}"
                                    data-time="{{ substr($trip->departure_time,0,5) }}"
                                    {{ old('trip_id', request('trip_id')) == $trip->id ? 'selected' : '' }}>
                                    {{ $trip->route->name }} — {{ substr($trip->departure_time,0,5) }}
                                    ({{ $trip->vessel_name ?? 'Vessel TBA' }})
                                </option>
                                @endforeach
                            </select>
                            @error('trip_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Travel Date <span class="req">*</span></label>
                            <input type="date" name="travel_date"
                                   class="form-control {{ $errors->has('travel_date') ? 'is-invalid' : '' }}"
                                   value="{{ old('travel_date') }}"
                                   min="{{ date('Y-m-d') }}" required>
                            @error('travel_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Method <span class="req">*</span></label>
                            <select name="payment_method" class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" required>
                                <option value="">Select…</option>
                                @foreach(['gcash'=>'GCash','maya'=>'Maya','visa'=>'Visa','mastercard'=>'Mastercard','cash'=>'Cash at Port'] as $v=>$l)
                                <option value="{{ $v }}" {{ old('payment_method')==$v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── Contact Info ────────────────────────────────────── --}}
                <div class="card">
                    <div class="card-title">👤 Contact Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="req">*</span></label>
                            <input type="text" name="contact_name" class="form-control {{ $errors->has('contact_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('contact_name') }}" placeholder="Juan dela Cruz" required>
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="req">*</span></label>
                            <input type="tel" name="contact_phone" class="form-control {{ $errors->has('contact_phone') ? 'is-invalid' : '' }}"
                                   value="{{ old('contact_phone') }}" placeholder="+63 917 123 4567" required>
                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label class="form-label">Email Address <span class="req">*</span></label>
                            <input type="email" name="contact_email" class="form-control {{ $errors->has('contact_email') ? 'is-invalid' : '' }}"
                                   value="{{ old('contact_email') }}" placeholder="juan@email.com" required>
                            <span class="fare-hint">Your booking confirmation and reference will be sent here.</span>
                            @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── Passengers ──────────────────────────────────────── --}}
                <div class="card">
                    <div class="card-title">🧍 Passengers</div>
                    <p class="section-note">Add each passenger individually. Discounted fares require valid ID at port.</p>

                    <div id="passengerList">
                        {{-- Passenger rows are injected by JS; default 1 --}}
                    </div>
                    <button type="button" class="btn-add" id="addPassenger">+ Add Another Passenger</button>
                </div>

                {{-- ── Vehicles (optional) ─────────────────────────────── --}}
                <div class="card">
                    <div class="card-title">🚗 Vehicles <span style="font-weight:400;font-size:.85rem;color:#9ca3af;">(optional)</span></div>
                    <p class="section-note">Adding a vehicle? Select the vehicle type and enter the plate number.</p>

                    <div id="vehicleList"></div>
                    <button type="button" class="btn-add" id="addVehicle">+ Add a Vehicle</button>
                </div>

                {{-- ── Total & Submit ──────────────────────────────────── --}}
                <div class="total-box">
                    <div>
                        <div class="total-label">Estimated Total</div>
                        <div class="total-amount">₱<span id="totalDisplay">0.00</span></div>
                    </div>
                    <div style="font-size:.78rem;opacity:.6;text-align:right;">Based on minimum vehicle fares.<br>Final amount confirmed at port.</div>
                </div>

                <button type="submit" class="btn-submit">✅ Confirm Booking</button>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-bottom" style="text-align:center;padding:1.5rem;">
            <p>&copy; {{ date('Y') }} Balanacan Port e-Transact. All rights reserved.</p>
        </div>
    </footer>

    {{-- Fare data for JS — prepared in controller to avoid Blade parser issues --}}
    <script>
        const ROUTES = @json($routesJs);
        const TRIPS  = @json($tripsJs);

        let passengerCount = 0;
        let vehicleCount   = 0;
        let selectedRouteId = null;

        // ── Route/Trip filtering ────────────────────────────────────────────
        const routeSel = document.getElementById('routeSelect');
        const tripSel  = document.getElementById('tripSelect');
        const allTripOptions = Array.from(tripSel.querySelectorAll('option[data-route]'));

        routeSel.addEventListener('change', () => {
            selectedRouteId = routeSel.value;
            filterTrips();
            refreshPassengerFareHints();
            refreshVehicleFareOptions();
            recalcTotal();
        });

        tripSel.addEventListener('change', () => {
            const opt = tripSel.options[tripSel.selectedIndex];
            if (opt && opt.dataset.route) {
                selectedRouteId = opt.dataset.route;
                routeSel.value  = selectedRouteId;
                refreshPassengerFareHints();
                refreshVehicleFareOptions();
                recalcTotal();
            }
        });

        function filterTrips() {
            allTripOptions.forEach(opt => {
                const show = !selectedRouteId || opt.dataset.route == selectedRouteId;
                opt.style.display = show ? '' : 'none';
            });
            // reset trip selection if hidden
            const sel = tripSel.options[tripSel.selectedIndex];
            if (sel && sel.dataset.route && sel.dataset.route != selectedRouteId) {
                tripSel.value = '';
            }
        }

        // Pre-select if ?trip_id= in URL
        const preTrip = tripSel.value;
        if (preTrip && TRIPS[preTrip]) {
            selectedRouteId = String(TRIPS[preTrip].route_id);
            routeSel.value  = selectedRouteId;
            filterTrips();
        }

        // ── Passengers ──────────────────────────────────────────────────────
        function fareOptions() {
            const fares = selectedRouteId && ROUTES[selectedRouteId]
                ? ROUTES[selectedRouteId].passengerFares : {};
            const types = {regular:'Regular',student:'Student',senior:'Senior Citizen',children:'Children (below 12)',pwd:'PWD'};
            return Object.entries(types).map(([v,l]) =>
                `<option value="${v}">${l}${fares[v] ? ' — ₱'+parseFloat(fares[v]).toFixed(2) : ''}</option>`
            ).join('');
        }

        function addPassenger(prefill={}) {
            const idx = passengerCount++;
            const div = document.createElement('div');
            div.className = 'pax-block';
            div.dataset.idx = idx;
            div.innerHTML = `
                <div class="pax-header">
                    <span class="pax-num">Passenger ${idx+1}</span>
                    ${idx > 0 ? `<button type="button" class="btn-remove" onclick="removePassenger(this)">✕ Remove</button>` : ''}
                </div>
                <div class="form-row triple">
                    <div class="form-group">
                        <label class="form-label">First Name <span class="req">*</span></label>
                        <input type="text" name="passengers[${idx}][first_name]" class="form-control" value="${prefill.first_name||''}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span class="req">*</span></label>
                        <input type="text" name="passengers[${idx}][last_name]" class="form-control" value="${prefill.last_name||''}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fare Type <span class="req">*</span></label>
                        <select name="passengers[${idx}][fare_type]" class="form-control fare-type-sel" onchange="recalcTotal()">
                            ${fareOptions()}
                        </select>
                    </div>
                </div>
                <div class="form-row" id="id-fields-${idx}" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">ID Type</label>
                        <input type="text" name="passengers[${idx}][id_type]" class="form-control" placeholder="e.g. School ID">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ID Number</label>
                        <input type="text" name="passengers[${idx}][id_number]" class="form-control" placeholder="ID number">
                    </div>
                </div>
            `;
            document.getElementById('passengerList').appendChild(div);

            // Show/hide ID fields based on fare type
            const fareTypeSel = div.querySelector('.fare-type-sel');
            fareTypeSel.addEventListener('change', () => {
                const needsId = ['student','senior','children','pwd'].includes(fareTypeSel.value);
                div.querySelector(`#id-fields-${idx}`).style.display = needsId ? 'grid' : 'none';
            });

            recalcTotal();
        }

        function removePassenger(btn) {
            btn.closest('.pax-block').remove();
            renumberPassengers();
            recalcTotal();
        }

        function renumberPassengers() {
            document.querySelectorAll('.pax-block').forEach((block, i) => {
                block.querySelector('.pax-num').textContent = `Passenger ${i+1}`;
            });
        }

        function refreshPassengerFareHints() {
            document.querySelectorAll('.fare-type-sel').forEach(sel => {
                const idx = sel.name.match(/\[(\d+)\]/)[1];
                const curVal = sel.value;
                sel.innerHTML = fareOptions();
                sel.value = curVal;
            });
        }

        document.getElementById('addPassenger').addEventListener('click', () => addPassenger());
        addPassenger(); // default: 1 passenger

        // ── Vehicles ────────────────────────────────────────────────────────
        function vehicleFareOptions() {
            if (!selectedRouteId || !ROUTES[selectedRouteId]) return '<option value="">— No route selected —</option>';
            const vf = ROUTES[selectedRouteId].vehicleFares;
            if (!vf.length) return '<option value="">— No vehicle fares for this route —</option>';
            return vf.map(f =>
                `<option value="${f.id}" data-min="${f.min}">${f.label} — ₱${parseFloat(f.min).toFixed(2)}–₱${parseFloat(f.max).toFixed(2)}</option>`
            ).join('');
        }

        function addVehicle() {
            const idx = vehicleCount++;
            const div = document.createElement('div');
            div.className = 'pax-block';
            div.innerHTML = `
                <div class="pax-header">
                    <span class="pax-num">Vehicle ${idx+1}</span>
                    <button type="button" class="btn-remove" onclick="this.closest('.pax-block').remove(); recalcTotal();">✕ Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vehicle Type <span class="req">*</span></label>
                        <select name="vehicles[${idx}][vehicle_fare_id]" class="form-control vehicle-fare-sel" onchange="recalcTotal()">
                            ${vehicleFareOptions()}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Plate Number <span class="req">*</span></label>
                        <input type="text" name="vehicles[${idx}][plate_number]" class="form-control" placeholder="e.g. ABC 1234" required>
                    </div>
                </div>
                <div class="form-row single">
                    <div class="form-group">
                        <label class="form-label">Vehicle Description</label>
                        <input type="text" name="vehicles[${idx}][vehicle_description]" class="form-control" placeholder="e.g. Red Toyota Vios">
                    </div>
                </div>
            `;
            document.getElementById('vehicleList').appendChild(div);
            recalcTotal();
        }

        function refreshVehicleFareOptions() {
            document.querySelectorAll('.vehicle-fare-sel').forEach(sel => {
                const curVal = sel.value;
                sel.innerHTML = vehicleFareOptions();
                sel.value = curVal;
            });
        }

        document.getElementById('addVehicle').addEventListener('click', addVehicle);

        // ── Total calculation ───────────────────────────────────────────────
        function recalcTotal() {
            let total = 0;
            const fares = selectedRouteId && ROUTES[selectedRouteId]
                ? ROUTES[selectedRouteId].passengerFares : {};

            document.querySelectorAll('.fare-type-sel').forEach(sel => {
                total += parseFloat(fares[sel.value] || 0);
            });

            document.querySelectorAll('.vehicle-fare-sel').forEach(sel => {
                const opt = sel.options[sel.selectedIndex];
                total += parseFloat(opt?.dataset.min || 0);
            });

            document.getElementById('totalDisplay').textContent = total.toFixed(2);
        }
    </script>
</body>
</html>