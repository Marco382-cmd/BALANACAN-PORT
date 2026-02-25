@extends('admin.layouts.admin')

@section('title', 'Booking #' . $booking->booking_reference)
@section('breadcrumb', 'Bookings → <strong>' . e($booking->booking_reference) . '</strong>')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div class="page-title">Booking #{{ $booking->booking_reference }}</div>
        <div class="page-sub">
            {{ $booking->trip->route->origin_name ?? '—' }} ⇄ {{ $booking->trip->route->destination_name ?? '—' }}
        </div>
    </div>
    <div style="display:flex;gap:.75rem;">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">← Back to Bookings</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

    {{-- Booking Info --}}
    <div class="card">
        <div class="card-header"><div class="card-title">📋 Booking Info</div></div>
        <div class="card-body">
            <table style="width:100%; font-size:.88rem;">
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;width:40%;">Reference</td>
                    <td><strong>{{ $booking->booking_reference }}</strong></td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Booking Status</td>
                    <td><span class="badge badge-{{ $booking->booking_status }}">{{ ucfirst($booking->booking_status) }}</span></td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Payment Status</td>
                    <td><span class="badge badge-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span></td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Payment Method</td>
                    <td>{{ ucfirst($booking->payment_method ?? '—') }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Total Amount</td>
                    <td style="font-weight:700;color:#0d6efd;">₱{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Travel Date</td>
                    <td>{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Booked On</td>
                    <td>{{ $booking->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                @if($booking->notes)
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Notes</td>
                    <td>{{ $booking->notes }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Contact & Trip Info --}}
    <div class="card">
        <div class="card-header"><div class="card-title">📞 Contact & Trip Info</div></div>
        <div class="card-body">
            <table style="width:100%; font-size:.88rem;">
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;width:40%;">Contact Name</td>
                    <td><strong>{{ $booking->contact_name }}</strong></td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Email</td>
                    <td><a href="mailto:{{ $booking->contact_email }}">{{ $booking->contact_email }}</a></td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Phone</td>
                    <td><a href="tel:{{ $booking->contact_phone }}">{{ $booking->contact_phone }}</a></td>
                </tr>
                <tr><td colspan="2" style="padding:.5rem 0;"><hr style="border:none;border-top:1px solid #f3f4f6;"></td></tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Route</td>
                    <td>{{ $booking->trip->route->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Origin</td>
                    <td><strong>{{ $booking->trip->route->origin_code ?? '' }}</strong> – {{ $booking->trip->route->origin_name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Destination</td>
                    <td><strong>{{ $booking->trip->route->destination_code ?? '' }}</strong> – {{ $booking->trip->route->destination_name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Departure</td>
                    <td>{{ $booking->trip->departure_formatted ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:#6c757d;padding:.4rem 0;">Vessel</td>
                    <td>{{ $booking->trip->vessel_name ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Passengers --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">🧍 Passengers ({{ $booking->passengers->count() }})</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Fare Type</th>
                        <th>ID Type</th>
                        <th>ID Number</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($booking->passengers as $i => $passenger)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $passenger->first_name }} {{ $passenger->last_name }}</strong></td>
                        <td><span class="badge badge-{{ $passenger->fare_type }}">{{ ucfirst($passenger->fare_type) }}</span></td>
                        <td>{{ $passenger->id_type ?? '—' }}</td>
                        <td>{{ $passenger->id_number ?? '—' }}</td>
                        <td style="font-weight:700;">₱{{ number_format($passenger->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#adb5bd;padding:1rem;">No passengers recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Vehicles --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">🚗 Vehicles ({{ $booking->vehicles->count() }})</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Plate No.</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($booking->vehicles as $i => $vehicle)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $vehicle->vehicle_type }}</td>
                        <td><strong>{{ $vehicle->plate_number ?? '—' }}</strong></td>
                        <td>{{ $vehicle->size_description ?? '—' }}</td>
                        <td style="font-weight:700;">₱{{ number_format($vehicle->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#adb5bd;padding:1rem;">No vehicles booked.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- QR Code --}}
@if($booking->qr_code_path)
<div class="card" style="margin-top:1.25rem;">
    <div class="card-header"><div class="card-title">🎫 QR Ticket</div></div>
    <div class="card-body" style="text-align:center;padding:2rem;">
        <img src="{{ asset('storage/' . $booking->qr_code_path) }}"
             alt="QR Code for {{ $booking->booking_reference }}"
             style="width:180px;height:180px;border:4px solid #e9ecef;border-radius:12px;padding:8px;">
        <p style="margin-top:.75rem;font-size:.85rem;color:#6c757d;">
            Booking Reference: <strong>{{ $booking->booking_reference }}</strong>
        </p>
    </div>
</div>
@endif

@endsection