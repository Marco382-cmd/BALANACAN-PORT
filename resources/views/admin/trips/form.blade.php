@extends('admin.layouts.admin')

@section('title', $edit ? 'Edit Trip' : 'Add Trip')
@section('breadcrumb', '')

@section('content')
<div class="page-header">
    <div class="page-title">{{ $edit ? '✏️ Edit Trip' : '➕ Add Trip' }}</div>
    <div class="page-sub">{{ $edit ? 'Update trip details.' : 'Create a new ferry trip schedule.' }}</div>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-header">
        <div class="card-title">Trip Details</div>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger" style="background:#fee2e2;border:1px solid #fca5a5;padding:12px 16px;border-radius:6px;margin-bottom:16px;color:#b91c1c;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin:6px 0 0 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ $edit ? route('admin.trips.update', $trip) : route('admin.trips.store') }}">
            @csrf
            @if($edit) @method('PUT') @endif

            <div class="form-grid">

                {{-- Route --}}
                <div class="form-group form-full">
                    <label class="form-label">Route <span class="required">*</span></label>
                    <select name="route_id" class="form-control {{ $errors->has('route_id') ? 'is-invalid' : '' }}" required>
                        <option value="">Select route…</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}"
                                {{ old('route_id', $trip?->route_id ?? request('route_id')) == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('route_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Departure Time --}}
                <div class="form-group">
                    <label class="form-label">Departure Time <span class="required">*</span></label>
                    <input type="time" name="departure_time"
                           class="form-control {{ $errors->has('departure_time') ? 'is-invalid' : '' }}"
                           value="{{ old('departure_time', isset($trip?->departure_time) ? substr($trip->departure_time, 0, 5) : '') }}"
                           required>
                    @error('departure_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Vessel Name --}}
                <div class="form-group">
                    <label class="form-label">Vessel Name</label>
                    <input type="text" name="vessel_name"
                           class="form-control {{ $errors->has('vessel_name') ? 'is-invalid' : '' }}"
                           value="{{ old('vessel_name', $trip?->vessel_name ?? '') }}"
                           placeholder="e.g. MV Marinduque Star">
                    @error('vessel_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Passenger Slots --}}
                <div class="form-group">
                    <label class="form-label">Passenger Slots <span class="required">*</span></label>
                    <input type="number" name="available_passenger_slots" min="0"
                           class="form-control {{ $errors->has('available_passenger_slots') ? 'is-invalid' : '' }}"
                           value="{{ old('available_passenger_slots', $trip?->available_passenger_slots ?? 200) }}"
                           required>
                    @error('available_passenger_slots') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Vehicle Slots --}}
                <div class="form-group">
                    <label class="form-label">Vehicle Slots <span class="required">*</span></label>
                    <input type="number" name="available_vehicle_slots" min="0"
                           class="form-control {{ $errors->has('available_vehicle_slots') ? 'is-invalid' : '' }}"
                           value="{{ old('available_vehicle_slots', $trip?->available_vehicle_slots ?? 20) }}"
                           required>
                    @error('available_vehicle_slots') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Status — must match DB enum: active, inactive, canceled --}}
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                        @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'canceled' => 'Canceled'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('status', $trip?->status ?? 'active') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group form-full">
                    <label class="form-label">Notes / Announcement</label>
                    <textarea name="notes" rows="3"
                              class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                              placeholder="Optional announcement for passengers…">{{ old('notes', $trip?->notes ?? '') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    {{ $edit ? '💾 Save Changes' : '➕ Create Trip' }}
                </button>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

    </div>
</div>
@endsection