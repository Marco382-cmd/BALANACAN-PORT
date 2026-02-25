@extends('admin.layouts.admin')

@section('title', $edit ? 'Edit Passenger Fare' : 'Add Passenger Fare')
@section('breadcrumb', 'Fares → <strong>{{ $edit ? "Edit Passenger Fare" : "Add Passenger Fare" }}</strong>')

@section('content')
<div class="page-header">
    <div class="page-title">{{ $edit ? '✏️ Edit Passenger Fare' : '+ Add Passenger Fare' }}</div>
    <div class="page-sub">Route: <strong>{{ $route->name }}</strong></div>
</div>

<div class="card" style="max-width:580px;">
    <div class="card-header"><div class="card-title">Fare Details</div></div>
    <div class="card-body">
        <form method="POST"
              action="{{ $edit ? route('admin.fares.passenger.update', $fare) : route('admin.fares.passenger.store', $route) }}">
            @csrf
            @if($edit) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Fare Type <span class="required">*</span></label>
                    <select name="fare_type" class="form-control {{ $errors->has('fare_type') ? 'is-invalid' : '' }}" required>
                        @foreach(['regular','student','senior','children','pwd'] as $t)
                        <option value="{{ $t }}" {{ old('fare_type', $fare->fare_type) === $t ? 'selected' : '' }}>
                            {{ ucfirst($t) }}
                        </option>
                        @endforeach
                    </select>
                    @error('fare_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Label <span class="required">*</span></label>
                    <input type="text" name="label"
                           class="form-control {{ $errors->has('label') ? 'is-invalid' : '' }}"
                           value="{{ old('label', $fare->label) }}"
                           placeholder="e.g. Regular, Student" required>
                    @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Amount (₱) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="amount" min="0"
                           class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                           value="{{ old('amount', $fare->amount) }}" required>
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Discount % <span class="required">*</span></label>
                    <input type="number" step="0.01" name="discount_pct" min="0" max="100"
                           class="form-control {{ $errors->has('discount_pct') ? 'is-invalid' : '' }}"
                           value="{{ old('discount_pct', $fare->discount_pct ?? 0) }}" required>
                    <div class="form-hint">0 = no discount</div>
                    @error('discount_pct') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group form-full">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes', $fare->notes) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    {{ $edit ? '💾 Save Changes' : '+ Add Fare' }}
                </button>
                <a href="{{ route('admin.fares.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection