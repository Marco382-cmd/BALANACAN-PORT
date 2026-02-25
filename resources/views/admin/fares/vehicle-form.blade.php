@extends('admin.layouts.admin')

@section('title', $edit ? 'Edit Vehicle Fare' : 'Add Vehicle Fare')
@section('breadcrumb', 'Fares → <strong>{{ $edit ? "Edit Vehicle Fare" : "Add Vehicle Fare" }}</strong>')

@section('content')
<div class="page-header">
    <div class="page-title">{{ $edit ? '✏️ Edit Vehicle Fare' : '+ Add Vehicle Fare' }}</div>
    <div class="page-sub">Route: <strong>{{ $route->name }}</strong></div>
</div>

<div class="card" style="max-width:620px;">
    <div class="card-header"><div class="card-title">Vehicle Fare Details</div></div>
    <div class="card-body">
        <form method="POST"
              action="{{ $edit ? route('admin.fares.vehicle.update', $fare) : route('admin.fares.vehicle.store', $route) }}">
            @csrf
            @if($edit) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Vehicle Type <span class="required">*</span></label>
                    <select name="vehicle_type" class="form-control {{ $errors->has('vehicle_type') ? 'is-invalid' : '' }}" required>
                        @foreach(['bicycle'=>'🚲 Bicycle','motorcycle'=>'🏍 Motorcycle','type2'=>'🚗 Type 2 (Sedan/SUV)','type3'=>'🚐 Type 3 (Van/Truck)','type4'=>'🚛 Type 4 (Heavy)'] as $v => $lbl)
                        <option value="{{ $v }}" {{ old('vehicle_type', $fare->vehicle_type) === $v ? 'selected' : '' }}>
                            {{ $lbl }}
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Label <span class="required">*</span></label>
                    <input type="text" name="label"
                           class="form-control {{ $errors->has('label') ? 'is-invalid' : '' }}"
                           value="{{ old('label', $fare->label) }}"
                           placeholder="e.g. Small Vehicle (Sedan/SUV)" required>
                    @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group form-full">
                    <label class="form-label">Size Description</label>
                    <input type="text" name="size_description"
                           class="form-control {{ $errors->has('size_description') ? 'is-invalid' : '' }}"
                           value="{{ old('size_description', $fare->size_description) }}"
                           placeholder="e.g. 3m – 5m">
                    @error('size_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Minimum Fare (₱) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="fare_min" min="0"
                           class="form-control {{ $errors->has('fare_min') ? 'is-invalid' : '' }}"
                           value="{{ old('fare_min', $fare->fare_min) }}" required>
                    @error('fare_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Maximum Fare (₱) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="fare_max" min="0"
                           class="form-control {{ $errors->has('fare_max') ? 'is-invalid' : '' }}"
                           value="{{ old('fare_max', $fare->fare_max) }}" required>
                    <div class="form-hint">Set same as min for fixed price</div>
                    @error('fare_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group form-full">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="2" class="form-control"
                              placeholder="e.g. Driver's fee included">{{ old('notes', $fare->notes) }}</textarea>
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