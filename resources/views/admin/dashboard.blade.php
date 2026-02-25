@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', '')

@section('content')
<div class="page-header">
    <div class="page-title">Dashboard</div>
    <div class="page-sub">Welcome back, {{ session('admin_name', 'Admin') }}. Here's what's happening today.</div>
</div>

{{-- ── Stats Grid ───────────────────────────────────────── --}}
<div class="stats-grid" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:2rem;">

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">🎫</div>
        <div>
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ number_format($stats['total_bookings'] ?? 0) }}</div>
            <div class="stat-sub">+{{ $stats['today_bookings'] ?? 0 }} today</div>
        </div>
    </div>

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">✅</div>
        <div>
            <div class="stat-label">Confirmed</div>
            <div class="stat-value">{{ number_format($stats['confirmed_bookings'] ?? 0) }}</div>
            <div class="stat-sub">Active bookings</div>
        </div>
    </div>

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">⏳</div>
        <div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ number_format($stats['pending_bookings'] ?? 0) }}</div>
            <div class="stat-sub">Awaiting payment</div>
        </div>
    </div>

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">💰</div>
        <div>
            <div class="stat-label">Revenue</div>
            <div class="stat-value">₱{{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
            <div class="stat-sub">₱{{ number_format($stats['today_revenue'] ?? 0, 0) }} today</div>
        </div>
    </div>

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">🚢</div>
        <div>
            <div class="stat-label">Active Trips</div>
            <div class="stat-value">{{ number_format($stats['active_trips'] ?? 0) }}</div>
            <div class="stat-sub">Upcoming trips</div>
        </div>
    </div>

    <div class="stat-card" style="flex:1; min-width:150px; background:#f0f4f8; padding:1rem; border-radius:0.5rem;">
        <div class="stat-icon" style="font-size:1.5rem;">🛳️</div>
        <div>
            <div class="stat-label">Total Routes</div>
            <div class="stat-value">{{ number_format($stats['total_routes'] ?? 0) }}</div>
            <div class="stat-sub">Active ferry routes</div>
        </div>
    </div>

</div>

{{-- ── Recent Bookings ───────────────────────────────────── --}}
<h3>Recent Bookings</h3>
<table class="table" style="width:100%; border-collapse:collapse; margin-bottom:2rem;">
    <thead>
        <tr style="background:#f0f4f8;">
            <th>ID</th>
            <th>Reference</th>
            <th>Trip</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($recentBookings as $booking)
            <tr style="border-bottom:1px solid #ddd;">
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->booking_reference }}</td>
                <td>{{ $booking->trip->route->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($booking->booking_status) }}</td>
                <td>{{ ucfirst($booking->payment_status) }}</td>
                <td>₱{{ number_format($booking->total_amount, 2) }}</td>
                <td>{{ $booking->created_at->format('M d, Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;">No recent bookings</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- ── Upcoming Trips ─────────────────────────────────────── --}}
<h3>Upcoming Trips</h3>
<table class="table" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#f0f4f8;">
            <th>Trip ID</th>
            <th>Route</th>
            <th>Departure</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($upcomingTrips as $trip)
            <tr style="border-bottom:1px solid #ddd;">
                <td>{{ $trip->id }}</td>
                <td>{{ $trip->route->name ?? 'N/A' }}</td>
                <td>{{ $trip->departure_time->format('M d, Y H:i') }}</td>
                <td>{{ ucfirst($trip->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center;">No upcoming trips</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection