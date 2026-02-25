@extends('admin.layouts.admin')

@section('title', 'Fare Guide')
@section('breadcrumb', '<strong>Fare Guide</strong>')

@section('content')

<style>
    /* ── Page header ───────────────────────────────────────────────── */
    .fare-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    /* ── Route tabs ────────────────────────────────────────────────── */
    .route-tabs {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0;
    }
    .route-tab {
        padding: .55rem 1.1rem;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        background: transparent;
        font-weight: 700;
        font-size: .85rem;
        color: #6b7280;
        cursor: pointer;
        transition: all .18s;
        border-radius: 6px 6px 0 0;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .route-tab:hover     { color: #0f2d4a; background: #f3f4f6; }
    .route-tab.is-active { color: #0f2d4a; border-bottom-color: #0f2d4a; background: transparent; }

    .route-panel { display: none; }
    .route-panel.is-active { display: block; }

    /* ── Stat summary strip ────────────────────────────────────────── */
    .fare-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: .75rem;
        margin-bottom: 1.5rem;
    }
    .fare-stat {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .85rem;
    }
    .fare-stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .fare-stat-icon.blue   { background: #dbeafe; }
    .fare-stat-icon.green  { background: #dcfce7; }
    .fare-stat-icon.amber  { background: #fef3c7; }
    .fare-stat-icon.purple { background: #ede9fe; }
    .fare-stat-body { line-height: 1.3; }
    .fare-stat-val  { font-size: 1.2rem; font-weight: 800; color: #0f2d4a; }
    .fare-stat-lbl  { font-size: .72rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; }

    /* ── Section heading inside panel ──────────────────────────────── */
    .fare-section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1.5rem 0 .85rem;
    }
    .fare-section-title {
        font-size: .9rem;
        font-weight: 800;
        color: #0f2d4a;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .fare-section-title .count-chip {
        font-size: .7rem;
        background: #e0f2fe;
        color: #0369a1;
        padding: .15rem .45rem;
        border-radius: 999px;
        font-weight: 700;
    }

    /* ── Passenger fare cards ──────────────────────────────────────── */
    .pfare-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: .85rem;
        margin-bottom: 1.5rem;
    }
    .pfare-card {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.25rem 1rem;
        text-align: center;
        position: relative;
        transition: all .2s;
    }
    .pfare-card:hover {
        border-color: #0f2d4a;
        box-shadow: 0 6px 20px rgba(15,45,74,.1);
        transform: translateY(-2px);
    }
    .pfare-card-icon { font-size: 2rem; margin-bottom: .45rem; }
    .pfare-card-type {
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .08em;
        color: #9ca3af; margin-bottom: .5rem;
    }
    .pfare-card-amount {
        font-size: 1.75rem; font-weight: 800;
        color: #0f2d4a; line-height: 1;
    }
    .pfare-card-amount sup { font-size: 1rem; font-weight: 700; }
    .pfare-card-discount {
        display: inline-block; margin-top: .4rem;
        font-size: .7rem; font-weight: 700;
        background: #dcfce7; color: #15803d;
        padding: .15rem .45rem; border-radius: 999px;
    }
    .pfare-card-id {
        font-size: .72rem; color: #9ca3af;
        margin-top: .5rem; line-height: 1.4;
    }
    .pfare-card-actions {
        display: flex;
        gap: .35rem;
        justify-content: center;
        margin-top: .85rem;
        padding-top: .75rem;
        border-top: 1px solid #f3f4f6;
    }
    .btn-xs {
        font-size: .72rem; font-weight: 700;
        padding: .25rem .6rem; border-radius: 6px;
        border: none; cursor: pointer; transition: all .15s;
        text-decoration: none; display: inline-block;
    }
    .btn-xs.edit   { background: #dbeafe; color: #1d4ed8; }
    .btn-xs.edit:hover { background: #bfdbfe; }
    .btn-xs.del    { background: #fee2e2; color: #b91c1c; }
    .btn-xs.del:hover  { background: #fecaca; }

    /* ── Vehicle fares table ───────────────────────────────────────── */
    .vfare-table-wrap { overflow-x: auto; border-radius: 12px; border: 1.5px solid #e5e7eb; }
    .vfare-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
    .vfare-table thead tr { background: #0f2d4a; }
    .vfare-table th {
        padding: .7rem 1rem; text-align: left;
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: #fff;
    }
    .vfare-table td { padding: .8rem 1rem; border-bottom: 1px solid #f3f4f6; color: #374151; }
    .vfare-table tbody tr:last-child td { border-bottom: none; }
    .vfare-table tbody tr:nth-child(even) td { background: #f9fafb; }
    .vfare-table tbody tr:hover td { background: #eff6ff; }

    .vtype-name { font-weight: 700; color: #0f2d4a; }
    .vsize-chip {
        display: inline-block; font-size: .7rem; font-weight: 600;
        background: #f3f4f6; color: #6b7280;
        padding: .15rem .45rem; border-radius: 5px;
    }
    .vfare-range { font-weight: 700; color: #0f2d4a; }
    .vfare-min   { color: #15803d; }
    .vfare-max   { color: #b45309; }
    .vfare-note  { font-size: .75rem; color: #9ca3af; }
    .td-actions  { display: flex; gap: .35rem; }

    /* ── Empty state ───────────────────────────────────────────────── */
    .empty-row td { text-align: center; padding: 2rem; color: #9ca3af; font-style: italic; }

    /* ── Disclaimer banner ─────────────────────────────────────────── */
    .fare-disclaimer {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: .85rem 1.1rem;
        font-size: .82rem;
        color: #92400e;
        margin-top: 1.25rem;
    }

    /* ── Status chip ───────────────────────────────────────────────── */
    .route-status {
        display: inline-block; font-size: .68rem; font-weight: 700;
        padding: .2rem .5rem; border-radius: 999px;
    }
    .route-status.active   { background: #dcfce7; color: #15803d; }
    .route-status.seasonal { background: #fef3c7; color: #92400e; }
    .route-status.inactive { background: #f3f4f6; color: #6b7280; }
</style>

<div class="fare-page-header">
    <div>
        <div class="page-title">💰 Fare Guide</div>
        <div class="page-sub" style="color:#6b7280;font-size:.85rem;margin-top:.2rem;">
            Official PPA fare matrix — manage passenger and vehicle rates per route.
        </div>
    </div>
    {{-- ✅ FIXED: Buttons are now OUTSIDE the foreach loop.
         They link to the first route by default; per-route add buttons
         are inside each panel below. --}}
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
        @if($routes->isNotEmpty())
            <a href="{{ route('admin.fares.passenger.create', ['route' => $routes->first()->id]) }}"
               class="btn btn-primary btn-sm">
                + Add Passenger Fare
            </a>
            <a href="{{ route('admin.fares.vehicle.create', ['route' => $routes->first()->id]) }}"
               class="btn btn-secondary btn-sm">
                + Add Vehicle Fare
            </a>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;color:#166534;font-size:.85rem;">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ── Route Tabs ──────────────────────────────────────────────────────── --}}
<div class="route-tabs" role="tablist">
    @foreach($routes as $i => $route)
    <button class="route-tab {{ $i === 0 ? 'is-active' : '' }}"
            role="tab"
            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
            data-tab="panel-{{ $route->id }}">
        {{ $route->name }}
        <span class="route-status {{ $route->status }}">{{ ucfirst($route->status) }}</span>
    </button>
    @endforeach
</div>

{{-- ── Per-route panels ─────────────────────────────────────────────────── --}}
@foreach($routes as $i => $route)
<div class="route-panel {{ $i === 0 ? 'is-active' : '' }}" id="panel-{{ $route->id }}">

    {{-- Stats strip --}}
    <div class="fare-stats">
        <div class="fare-stat">
            <div class="fare-stat-icon blue">🧍</div>
            <div class="fare-stat-body">
                <div class="fare-stat-val">{{ $route->passengerFares->count() }}</div>
                <div class="fare-stat-lbl">Passenger Tiers</div>
            </div>
        </div>
        <div class="fare-stat">
            <div class="fare-stat-icon green">🚗</div>
            <div class="fare-stat-body">
                <div class="fare-stat-val">{{ $route->vehicleFares->count() }}</div>
                <div class="fare-stat-lbl">Vehicle Types</div>
            </div>
        </div>
        <div class="fare-stat">
            <div class="fare-stat-icon amber">💰</div>
            <div class="fare-stat-body">
                <div class="fare-stat-val">₱{{ number_format($route->passengerFares->where('fare_type','regular')->first()?->amount ?? 0, 0) }}</div>
                <div class="fare-stat-lbl">Base Pax Fare</div>
            </div>
        </div>
        <div class="fare-stat">
            <div class="fare-stat-icon purple">🚛</div>
            <div class="fare-stat-body">
                <div class="fare-stat-val">
                    ₱{{ number_format($route->vehicleFares->min('fare_min') ?? 0, 0) }}
                    <span style="font-size:.8rem;font-weight:500;color:#9ca3af;">min</span>
                </div>
                <div class="fare-stat-lbl">Vehicle From</div>
            </div>
        </div>
    </div>

    {{-- ── Passenger Fares ─────────────────────────────────────────── --}}
    <div class="fare-section-head">
        <div class="fare-section-title">
            🧍 Passenger Fares
            <span class="count-chip">{{ $route->passengerFares->count() }} tiers</span>
        </div>
        {{-- ✅ FIXED: correct named parameter 'route' --}}
        <a href="{{ route('admin.fares.passenger.create', ['route' => $route->id]) }}"
           class="btn btn-sm btn-secondary" style="font-size:.78rem;">+ Add Tier</a>
    </div>

    <div class="pfare-grid">
        @forelse($route->passengerFares->sortBy(fn($f) => match($f->fare_type){'regular'=>0,'student'=>1,'senior'=>2,'children'=>3,'pwd'=>4,default=>5}) as $fare)
        <div class="pfare-card">
            <div class="pfare-card-icon">{{ $fare->icon }}</div>
            <div class="pfare-card-type">{{ $fare->label }}</div>
            <div class="pfare-card-amount"><sup>₱</sup>{{ number_format($fare->amount, 2) }}</div>
            @if($fare->discount_pct > 0)
                <div class="pfare-card-discount">{{ number_format($fare->discount_pct, 1) }}% discount</div>
            @endif
            <div class="pfare-card-id">🪪 {{ $fare->required_id }}</div>
            @if($fare->notes)
                <div style="font-size:.7rem;color:#9ca3af;margin-top:.3rem;">{{ $fare->notes }}</div>
            @endif
            <div class="pfare-card-actions">
                <a href="{{ route('admin.fares.passenger.edit', $fare->id) }}" class="btn-xs edit">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.fares.passenger.destroy', $fare->id) }}"
                      onsubmit="return confirm('Delete this fare tier?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-xs del">🗑 Del</button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:2rem;color:#9ca3af;">
            No passenger fares set for this route.
            <a href="{{ route('admin.fares.passenger.create', ['route' => $route->id]) }}" style="color:#0369a1;">Add one →</a>
        </div>
        @endforelse
    </div>

    {{-- ── Vehicle Fares ───────────────────────────────────────────── --}}
    <div class="fare-section-head">
        <div class="fare-section-title">
            🚗 Vehicle Fares
            <span class="count-chip">{{ $route->vehicleFares->count() }} types</span>
        </div>
        {{-- ✅ FIXED: correct named parameter 'route' --}}
        <a href="{{ route('admin.fares.vehicle.create', ['route' => $route->id]) }}"
           class="btn btn-sm btn-secondary" style="font-size:.78rem;">+ Add Type</a>
    </div>

    <div class="vfare-table-wrap">
        <table class="vfare-table">
            <thead>
                <tr>
                    <th>Vehicle Type</th>
                    <th>Size</th>
                    <th>Min Fare</th>
                    <th>Max Fare</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($route->vehicleFares as $vf)
                <tr>
                    <td>
                        <span style="font-size:1.1rem;margin-right:.35rem;">{{ $vf->icon }}</span>
                        <span class="vtype-name">{{ $vf->label }}</span>
                    </td>
                    <td>
                        @if($vf->size_description)
                            <span class="vsize-chip">{{ $vf->size_description }}</span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td><span class="vfare-min">₱{{ number_format($vf->fare_min, 2) }}</span></td>
                    <td><span class="vfare-max">₱{{ number_format($vf->fare_max, 2) }}</span></td>
                    <td><span class="vfare-note">{{ $vf->notes ?? '—' }}</span></td>
                    <td>
                        <div class="td-actions">
                            {{-- ✅ FIXED: correct named parameter for vehicle fare edit/destroy --}}
                            <a href="{{ route('admin.fares.vehicle.edit', $vf->id) }}" class="btn-xs edit">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.fares.vehicle.destroy', $vf->id) }}"
                                  onsubmit="return confirm('Delete this vehicle fare?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-xs del">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">No vehicle fares set for this route.
                        <a href="{{ route('admin.fares.vehicle.create', ['route' => $route->id]) }}" style="color:#0369a1;">Add one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="fare-disclaimer">
        📌 <strong>Reminder:</strong> Driver's fee is included in all vehicle fares. Discounts for Student, Senior Citizen (60+), PWD, and Children apply to passenger fares only — valid ID must be presented at port checkout.
    </div>

</div>
@endforeach

<script>
    document.querySelectorAll('.route-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.route-tab').forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.route-panel').forEach(p => p.classList.remove('is-active'));
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');
            document.getElementById(tab.dataset.tab)?.classList.add('is-active');
        });
    });
</script>

@endsection