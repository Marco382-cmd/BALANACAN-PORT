@extends('admin.layouts.admin')

@section('title', $edit ? 'Edit Route' : 'Add Route')
@section('breadcrumb', '')

@section('content')
<div class="page-header">
    <div class="page-title">{{ $edit ? '✏️ Edit Route' : '+ Add Route' }}</div>
    <div class="page-sub">{{ $edit ? 'Update route details below.' : 'Create a new ferry route.' }}</div>
</div>

<div class="card" style="max-width:780px;">
    <div class="card-header">
        <div class="card-title">Route Details</div>
    </div>
    <div class="card-body">
        <form method="POST"
              action="{{ $edit ? route('admin.routes.update', $route) : route('admin.routes.store') }}">
            @csrf
            @if($edit) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group form-full">
                    <label class="form-label">Route Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $route->name) }}"
                           placeholder="e.g. Balanacan – Lucena" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Origin --}}
                <div class="form-group">
                    <label class="form-label">Origin Port Code <span class="required">*</span></label>
                    <input type="text" name="origin_code" maxlength="3"
                           class="form-control {{ $errors->has('origin_code') ? 'is-invalid' : '' }}"
                           value="{{ old('origin_code', $route->origin_code) }}"
                           placeholder="BLC" style="text-transform:uppercase;" required>
                    <div class="form-hint">3-letter code (e.g. BLC)</div>
                    @error('origin_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Origin Port Name <span class="required">*</span></label>
                    <input type="text" name="origin_name"
                           class="form-control {{ $errors->has('origin_name') ? 'is-invalid' : '' }}"
                           value="{{ old('origin_name', $route->origin_name) }}"
                           placeholder="e.g. Balanacan" required>
                    @error('origin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group form-full">
                    <label class="form-label">Origin Location <span class="required">*</span></label>
                    <input type="text" name="origin_location"
                           class="form-control {{ $errors->has('origin_location') ? 'is-invalid' : '' }}"
                           value="{{ old('origin_location', $route->origin_location) }}"
                           placeholder="e.g. Mogpog, Marinduque" required>
                    @error('origin_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Destination --}}
                <div class="form-group">
                    <label class="form-label">Destination Port Code <span class="required">*</span></label>
                    <input type="text" name="destination_code" maxlength="3"
                           class="form-control {{ $errors->has('destination_code') ? 'is-invalid' : '' }}"
                           value="{{ old('destination_code', $route->destination_code) }}"
                           placeholder="LCN" style="text-transform:uppercase;" required>
                    @error('destination_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Destination Port Name <span class="required">*</span></label>
                    <input type="text" name="destination_name"
                           class="form-control {{ $errors->has('destination_name') ? 'is-invalid' : '' }}"
                           value="{{ old('destination_name', $route->destination_name) }}"
                           placeholder="e.g. Lucena" required>
                    @error('destination_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group form-full">
                    <label class="form-label">Destination Location <span class="required">*</span></label>
                    <input type="text" name="destination_location"
                           class="form-control {{ $errors->has('destination_location') ? 'is-invalid' : '' }}"
                           value="{{ old('destination_location', $route->destination_location) }}"
                           placeholder="e.g. Quezon Province" required>
                    @error('destination_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Duration (minutes) <span class="required">*</span></label>
                    <input type="number" name="duration_minutes" min="1"
                           class="form-control {{ $errors->has('duration_minutes') ? 'is-invalid' : '' }}"
                           value="{{ old('duration_minutes', $route->duration_minutes) }}" required>
                    @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Trips Per Day <span class="required">*</span></label>
                    <input type="number" name="trips_per_day" min="1"
                           class="form-control {{ $errors->has('trips_per_day') ? 'is-invalid' : '' }}"
                           value="{{ old('trips_per_day', $route->trips_per_day ?? 1) }}" required>
                    @error('trips_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                        @foreach(['active','seasonal','inactive'] as $s)
                        <option value="{{ $s }}" {{ old('status', $route->status) === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    {{ $edit ? '💾 Save Changes' : '+ Create Route' }}
                </button>
                <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection