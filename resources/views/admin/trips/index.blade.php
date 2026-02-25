@extends('admin.layouts.admin')

@section('title', 'Trips')
@section('breadcrumb', '')

@section('content')
<div class="page-header">
    <div class="page-title">Trips / Schedule</div>
    <div class="page-sub">Manage ferry trips and schedules.</div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background:#dcfce7;border:1px solid #86efac;padding:10px 16px;border-radius:6px;margin-bottom:16px;color:#166534;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">All Trips</div>
        <a href="{{ route('admin.trips.create') }}" class="btn btn-primary btn-sm">➕ Add Trip</a>
    </div>
    <div class="card-body table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Route</th>
                    <th>Departure Time</th>
                    <th>Vessel</th>
                    <th>Pax Slots</th>
                    <th>Vehicle Slots</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                <tr>
                    <td>{{ $trip->id }}</td>
                    <td>{{ $trip->route->name ?? 'N/A' }}</td>
                    <td>{{ substr($trip->departure_time, 0, 5) }}</td>
                    <td>{{ $trip->vessel_name ?? '—' }}</td>
                    <td>{{ $trip->available_passenger_slots }}</td>
                    <td>{{ $trip->available_vehicle_slots }}</td>
                    <td>{{ ucfirst($trip->status) }}</td>
                    <td>
                        <a href="{{ route('admin.trips.edit', $trip->id) }}" class="btn btn-sm btn-success">Edit</a>
                        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" style="display:inline-block;"
                              onsubmit="return confirm('Delete this trip?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">No trips found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection