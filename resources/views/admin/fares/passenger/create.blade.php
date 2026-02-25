{{-- resources/views/admin/fares/passenger/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Add Passenger Fare')
@section('breadcrumb', 'Fares → Passenger → Create')

@section('content')
<div class="page-header">
    <div class="page-title">Add Passenger Fare for Route: {{ $route->name }}</div>
</div>

<form method="POST" action="{{ route('admin.fares.passenger.store', $route) }}">
    @csrf

    <div class="form-group">
        <label>Fare Type</label>
        <select name="fare_type" class="form-control">
            @foreach(['regular','student','senior','pwd','children'] as $type)
                <option value="{{ $type }}" {{ old('fare_type') === $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Label</label>
        <input type="text" name="label" class="form-control" value="{{ old('label') }}">
    </div>

    <div class="form-group">
        <label>Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
    </div>

    <div class="form-group">
        <label>Discount %</label>
        <input type="number" step="0.01" name="discount_pct" class="form-control" value="{{ old('discount_pct') }}">
    </div>

    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Add Fare</button>
    <a href="{{ route('admin.fares.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection