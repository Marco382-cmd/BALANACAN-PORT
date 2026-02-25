{{-- resources/views/admin/fares/passenger/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Passenger Fare')
@section('breadcrumb', 'Fares → Passenger → Edit')

@section('content')
<div class="page-header">
    <div class="page-title">Edit Passenger Fare for Route: {{ $route->name }}</div>
</div>

<form method="POST" action="{{ route('admin.fares.passenger.update', $fare) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Fare Type</label>
        <select name="fare_type" class="form-control">
            @foreach(['regular','student','senior','pwd','children'] as $type)
                <option value="{{ $type }}" {{ $fare->fare_type === $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Label</label>
        <input type="text" name="label" class="form-control" value="{{ old('label', $fare->label) }}">
    </div>

    <div class="form-group">
        <label>Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $fare->amount) }}">
    </div>

    <div class="form-group">
        <label>Discount %</label>
        <input type="number" step="0.01" name="discount_pct" class="form-control" value="{{ old('discount_pct', $fare->discount_pct) }}">
    </div>

    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes', $fare->notes) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Fare</button>
    <a href="{{ route('admin.fares.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection