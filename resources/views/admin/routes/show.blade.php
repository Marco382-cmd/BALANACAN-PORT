@extends('admin.layouts.admin')

@section('title', $route->name)
@section('breadcrumb', '')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $route->name }}</div>
        <div class="page-sub">
            Origin: {{ $route->origin_name }} ({{ $route->origin_code }}) → 
            Destination: {{ $route->destination_name }} ({{ $route->destination_code }})
        </div>
    </div>
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">← Back</a>
</div>

{{-- Example: trips for this route --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header"><div class="card-title">Trips</div></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>Departure</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($route->trips as $trip)
                <tr>
                    <td>{{ $trip->id }}</td>
                    <td>{{ $trip->departure_time }}</td>
                    <td>{{ $trip->status }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;">No trips for this route.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection