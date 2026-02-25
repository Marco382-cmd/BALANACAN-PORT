{{-- resources/views/admin/trips/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Add Trip')

@section('breadcrumb', '<strong>Trips / Add</strong>')

@section('content')
<div class="page-header">
    <div class="page-title">Add Trip</div>
    <div class="page-sub">Create a new ferry trip schedule.</div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.trips.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="route_id">Route</label>
                <select name="route_id" id="route_id" class="form-control" required>
                    @foreach(\App\Models\Route::all() as $route)
                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="departure_time">Departure Time</label>
                <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="scheduled">Scheduled</option>
                    <option value="boarding">Boarding</option>
                    <option value="departed">Departed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Trip</button>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection