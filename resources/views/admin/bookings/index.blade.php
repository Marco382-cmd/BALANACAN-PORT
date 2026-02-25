@extends('admin.layouts.admin')

@section('title', 'Bookings')
@section('breadcrumb', 'Operations → <strong>Bookings</strong>')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div class="page-title">🎫 Bookings</div>
        <div class="page-sub">View and manage all passenger bookings.</div>
    </div>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">+ Manual Booking</a>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.25rem;">
    <form method="GET" class="filter-bar">
        <div class="form-group" style="flex:2; min-width:200px;">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control"
                   value="{{ request('search') }}"
                   placeholder="Ref, name, email, phone…">
        </div>
        <div class="form-group">
            <label class="form-label">Route</label>
            <select name="route_id" class="form-control">
                <option value="">All</option>
                @foreach($routes as $r)
                <option value="{{ $r->id }}" {{ request('route_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Payment</label>
            <select name="payment_status" class="form-control">
                <option value="">All</option>
                @foreach(['pending','paid','failed','refunded'] as $s)
                <option value="{{ $s }}" {{ request('payment_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Booking Status</label>
            <select name="booking_status" class="form-control">
                <option value="">All</option>
                @foreach(['pending','confirmed','cancelled','boarded'] as $s)
                <option value="{{ $s }}" {{ request('booking_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="form-group">
            <label class="form-label">To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div style="display:flex;gap:.5rem;align-items:flex-end;">
            <button type="submit" class="btn btn-primary">🔍</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="card">
    <div style="padding:.75rem 1.25rem; border-bottom:1px solid var(--gray200); font-size:.82rem; color:#6c757d;">
        Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Contact</th>
                    <th>Route</th>
                    <th>Travel Date</th>
                    <th>Pax</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                           style="color:#0d6efd;font-weight:700;font-size:.82rem;text-decoration:none;">
                            {{ $booking->booking_reference }}
                        </a>
                        <div style="font-size:.7rem;color:#adb5bd;">{{ $booking->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.85rem;">{{ $booking->contact_name }}</div>
                        <div style="font-size:.72rem;color:#6c757d;">{{ $booking->contact_phone }}</div>
                    </td>
                    <td style="font-size:.82rem;">{{ $booking->trip->route->name ?? '—' }}</td>
                    <td style="font-size:.82rem;white-space:nowrap;">{{ $booking->travel_date->format('M d, Y') }}</td>
                    <td>{{ $booking->total_passengers }}</td>
                    <td style="font-weight:800;color:#0f2d4a;">₱{{ number_format($booking->total_amount,0) }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->payment_status }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $booking->booking_status }}">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:#adb5bd;padding:2rem;">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="pagination-wrap">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection