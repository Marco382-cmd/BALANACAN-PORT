@extends('admin.layouts.admin')

@section('title', 'Routes')
@section('breadcrumb', '')

@section('content')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
    <div>
        <div class="page-title">🗺 Ferry Routes</div>
        <div class="page-sub">Manage all ferry routes and their configurations.</div>
    </div>
    <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">+ Add Route</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Route Name</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Trips/Day</th>
                    <th>Status</th>
                    <th>Fares</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routes as $route)
                <tr>
                    <td style="font-weight:700; color:#0f2d4a;">{{ $route->name }}</td>
                    <td>
                        <div style="font-weight:700;">{{ $route->origin_code }}</div>
                        <div style="font-size:.75rem; color:#6c757d;">{{ $route->origin_location }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700;">{{ $route->destination_code }}</div>
                        <div style="font-size:.75rem; color:#6c757d;">{{ $route->destination_location }}</div>
                    </td>
                    <td>{{ $route->duration_label }}</td>
                    <td>{{ $route->trips_per_day }}×</td>
                    <td><span class="badge badge-{{ $route->status }}">{{ ucfirst($route->status) }}</span></td>
                    <td>
                        <div style="font-size:.78rem; line-height:1.7;">
                            👤 {{ $route->passengerFares->count() }} fare types<br>
                            🚗 {{ $route->vehicleFares->count() }} vehicle types
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; gap:.4rem; flex-wrap:wrap;">
                            <a href="{{ route('admin.routes.show', $route) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.routes.destroy', $route) }}"
                                  onsubmit="return confirm('Delete route {{ $route->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#adb5bd; padding:2rem;">
                        No routes found. <a href="{{ route('admin.routes.create') }}">Add one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection