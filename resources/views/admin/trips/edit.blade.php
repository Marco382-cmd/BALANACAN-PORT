@extends('admin.layouts.admin')

@section('title', 'Edit Trip')
@section('breadcrumb', 'Trips → <strong>Edit Trip</strong>')

@section('content')
<div class="card">
    <div class="card-header"><div class="card-title">✏️ Edit Trip #{{ $trip->id }}</div></div>
    <div class="card-body">
        <form action="{{ route('admin.trips.update', $trip) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="route_id">Route</label>
                <select name="route_id" id="route_id" class="form-control">
                    @foreach(\App\Models\Route::all() as $route)
                        <option value="{{ $route->id }}" {{ $trip->route_id == $route->id ? 'selected' : '' }}>
                            {{ $route->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="departure_time">Departure Time</label>
                <input type="datetime-local" name="departure_time" id="departure_time" class="form-control"
                       value="{{ \Carbon\Carbon::parse($trip->departure_time)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    @foreach(['active','inactive','canceled'] as $status)
                        <option value="{{ $status }}" {{ $trip->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Update Trip</button>
        </form>
    </div>
</div>
@endsection