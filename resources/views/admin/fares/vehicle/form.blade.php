@extends('admin.layouts.admin')

@section('title', $edit ? 'Edit Vehicle Fare' : 'Add Vehicle Fare')
@section('breadcrumb', 'Fares → <strong>' . ($edit ? 'Edit Vehicle Fare' : 'Add Vehicle Fare') . '</strong>')

@section('content')

<style>
    /* ── Page layout ── */
    .vfare-page {
        max-width: 680px;
        margin: 0 auto;
    }

    /* ── Page header ── */
    .vfare-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .vfare-page-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f2d4a;
        letter-spacing: -.02em;
    }
    .vfare-page-sub {
        font-size: .85rem;
        color: #6b7280;
        margin-top: .2rem;
    }
    .vfare-page-sub strong { color: #0f2d4a; }

    /* ── Card ── */
    .vfare-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(15,45,74,.07);
    }

    /* ── Card top banner ── */
    .vfare-banner {
        background: linear-gradient(135deg, #0f2d4a 0%, #1a4a72 60%, #1e6091 100%);
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    .vfare-banner::before {
        content: '🚗';
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: .12;
        pointer-events: none;
    }
    .vfare-banner-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,.15);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .vfare-banner-text h2 {
        color: #fff;
        font-size: 1rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: -.01em;
    }
    .vfare-banner-text p {
        color: rgba(255,255,255,.6);
        font-size: .8rem;
        margin: .2rem 0 0;
    }

    /* ── Form body ── */
    .vfare-form-body {
        padding: 2rem;
    }

    /* ── Error banner ── */
    .vfare-errors {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 10px;
        padding: .85rem 1rem;
        margin-bottom: 1.5rem;
        font-size: .83rem;
        color: #b91c1c;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    /* ── Section divider ── */
    .vfare-section-label {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #9ca3af;
        margin: 1.75rem 0 1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .vfare-section-label:first-of-type { margin-top: 0; }

    /* ── Form group ── */
    .fg { margin-bottom: 1.25rem; }
    .fg-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .fg label {
        display: block;
        font-size: .78rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: .45rem;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .fg label .req { color: #ef4444; margin-left: .15rem; }
    .fg label .opt {
        font-weight: 500;
        color: #9ca3af;
        text-transform: none;
        letter-spacing: 0;
        font-size: .72rem;
        margin-left: .3rem;
    }

    /* ── Inputs ── */
    .fc {
        width: 100%;
        padding: .65rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: .9rem;
        color: #111827;
        background: #fafafa;
        transition: border-color .15s, box-shadow .15s, background .15s;
        box-sizing: border-box;
        font-family: inherit;
    }
    .fc:focus {
        outline: none;
        border-color: #0f2d4a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(15,45,74,.08);
    }
    .fc.is-invalid {
        border-color: #ef4444;
        background: #fff5f5;
    }
    .fc.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239,68,68,.1);
    }
    textarea.fc { resize: vertical; min-height: 90px; }

    .err-msg {
        font-size: .75rem;
        color: #ef4444;
        margin-top: .3rem;
        display: flex;
        align-items: center;
        gap: .25rem;
    }
    .hint {
        font-size: .74rem;
        color: #9ca3af;
        margin-top: .3rem;
        line-height: 1.4;
    }

    /* ── Vehicle type visual picker ── */
    .vtype-picker {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .5rem;
    }
    .vtype-radio { display: none; }
    .vtype-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .3rem;
        padding: .85rem .5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all .15s;
        background: #fafafa;
        text-align: center;
    }
    .vtype-pill .vp-icon { font-size: 1.6rem; line-height: 1; }
    .vtype-pill .vp-label {
        font-size: .7rem;
        font-weight: 700;
        color: #6b7280;
        letter-spacing: .03em;
    }
    .vtype-radio:checked + .vtype-pill {
        border-color: #0f2d4a;
        background: #eff6ff;
    }
    .vtype-radio:checked + .vtype-pill .vp-label { color: #0f2d4a; }
    .vtype-pill:hover {
        border-color: #93c5fd;
        background: #f0f9ff;
    }

    /* ── Fare range visual ── */
    .fare-range-row {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: .75rem;
        align-items: center;
    }
    .fare-range-sep {
        text-align: center;
        font-size: .75rem;
        font-weight: 700;
        color: #9ca3af;
        padding-top: 1.6rem;
    }
    .fare-prefix-wrap { position: relative; }
    .fare-prefix {
        position: absolute;
        left: .85rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: .85rem;
        font-weight: 700;
        color: #9ca3af;
        pointer-events: none;
    }
    .fare-prefix-wrap .fc { padding-left: 1.85rem; }

    /* ── Footer ── */
    .vfare-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: .75rem;
        padding: 1.25rem 2rem;
        border-top: 1.5px solid #f3f4f6;
        background: #fafafa;
    }

    /* ── Buttons ── */
    .btn-cancel {
        padding: .6rem 1.25rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        color: #6b7280;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-cancel:hover { border-color: #9ca3af; color: #374151; background: #f9fafb; }

    .btn-submit {
        padding: .65rem 1.5rem;
        border: none;
        border-radius: 10px;
        background: #0f2d4a;
        color: #fff;
        font-size: .88rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: .4rem;
        letter-spacing: .01em;
    }
    .btn-submit:hover {
        background: #1a4a72;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,45,74,.25);
    }
    .btn-submit:active { transform: translateY(0); }

    @media (max-width: 520px) {
        .vtype-picker { grid-template-columns: repeat(4, 1fr); }
        .fg-row { grid-template-columns: 1fr; }
        .fare-range-row { grid-template-columns: 1fr; }
        .fare-range-sep { display: none; }
        .vfare-form-body { padding: 1.25rem; }
        .vfare-footer { padding: 1rem 1.25rem; }
    }
</style>

<div class="vfare-page">

    {{-- Page header --}}
    <div class="vfare-page-header">
        <div>
            <div class="vfare-page-title">
                {{ $edit ? '✏️ Edit Vehicle Fare' : '🚗 Add Vehicle Fare' }}
            </div>
            <div class="vfare-page-sub">
                Route: <strong>{{ $route->name }}</strong>
                &nbsp;·&nbsp;
                {{ $route->origin_name }} → {{ $route->destination_name }}
            </div>
        </div>
        <a href="{{ route('admin.fares.index') }}" class="btn-cancel">← Back to Fares</a>
    </div>

    {{-- Card --}}
    <div class="vfare-card">

        {{-- Banner --}}
        <div class="vfare-banner">
            <div class="vfare-banner-icon">🚗</div>
            <div class="vfare-banner-text">
                <h2>{{ $edit ? 'Update Vehicle Fare' : 'New Vehicle Fare' }}</h2>
                <p>{{ $route->origin_name }} ⇄ {{ $route->destination_name }} &nbsp;·&nbsp; {{ $route->name }}</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="vfare-form-body">

            @if($errors->any())
            <div class="vfare-errors">
                ⚠️ Please fix the highlighted errors before saving.
            </div>
            @endif

            <form method="POST"
                  action="{{ $edit
                    ? route('admin.fares.vehicle.update', $fare->id)
                    : route('admin.fares.vehicle.store', $route->id) }}">
                @csrf
                @if($edit) @method('PUT') @endif

                {{-- Vehicle Type --}}
                <div class="vfare-section-label">Vehicle Type</div>
                <div class="fg">
                    <label>Select Type <span class="req">*</span></label>
                    <div class="vtype-picker">
                        @foreach([
                            ['value'=>'bicycle',    'icon'=>'🚲', 'label'=>'Bicycle'],
                            ['value'=>'motorcycle', 'icon'=>'🏍',  'label'=>'Motorcycle'],
                            ['value'=>'car',        'icon'=>'🚗', 'label'=>'Car'],
                            ['value'=>'van',        'icon'=>'🚐', 'label'=>'Van'],
                            ['value'=>'suv',        'icon'=>'🚙', 'label'=>'SUV'],
                            ['value'=>'pickup',     'icon'=>'🛻', 'label'=>'Pickup'],
                            ['value'=>'truck',      'icon'=>'🚚', 'label'=>'Truck'],
                            ['value'=>'bus',        'icon'=>'🚌', 'label'=>'Bus'],
                        ] as $vt)
                        <div>
                            <input type="radio" name="vehicle_type"
                                   id="vt_{{ $vt['value'] }}"
                                   value="{{ $vt['value'] }}"
                                   class="vtype-radio"
                                   {{ old('vehicle_type', $fare?->vehicle_type) === $vt['value'] ? 'checked' : '' }}>
                            <label for="vt_{{ $vt['value'] }}" class="vtype-pill">
                                <span class="vp-icon">{{ $vt['icon'] }}</span>
                                <span class="vp-label">{{ $vt['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('vehicle_type')
                        <div class="err-msg">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Label & Size --}}
                <div class="vfare-section-label">Details</div>
                <div class="fg-row">
                    <div class="fg">
                        <label>Display Label <span class="opt">(optional)</span></label>
                        <input type="text" name="label"
                               class="fc @error('label') is-invalid @enderror"
                               value="{{ old('label', $fare?->label) }}"
                               placeholder="e.g. Family Car, Big Truck…">
                        @error('label') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                        <p class="hint">Friendly name shown to passengers.</p>
                    </div>
                    <div class="fg">
                        <label>Size Description <span class="opt">(optional)</span></label>
                        <input type="text" name="size_description"
                               class="fc @error('size_description') is-invalid @enderror"
                               value="{{ old('size_description', $fare?->size_description) }}"
                               placeholder="e.g. Up to 1800cc, under 3.5T…">
                        @error('size_description') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                        <p class="hint">Engine size, weight class, or dimensions.</p>
                    </div>
                </div>

                {{-- Fare Range --}}
                <div class="vfare-section-label">Fare Range (₱)</div>
                <div class="fare-range-row">
                    <div class="fg" style="margin:0;">
                        <label>Minimum Fare <span class="req">*</span></label>
                        <div class="fare-prefix-wrap">
                            <span class="fare-prefix">₱</span>
                            <input type="number" name="fare_min" step="0.01" min="0"
                                   class="fc @error('fare_min') is-invalid @enderror"
                                   value="{{ old('fare_min', $fare?->fare_min) }}"
                                   placeholder="0.00">
                        </div>
                        @error('fare_min') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                    </div>
                    <div class="fare-range-sep">to</div>
                    <div class="fg" style="margin:0;">
                        <label>Maximum Fare <span class="opt">(optional)</span></label>
                        <div class="fare-prefix-wrap">
                            <span class="fare-prefix">₱</span>
                            <input type="number" name="fare_max" step="0.01" min="0"
                                   class="fc @error('fare_max') is-invalid @enderror"
                                   value="{{ old('fare_max', $fare?->fare_max) }}"
                                   placeholder="0.00">
                        </div>
                        @error('fare_max') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                        <p class="hint">Leave blank if fare is a fixed rate.</p>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="vfare-section-label">Additional Info</div>
                <div class="fg">
                    <label>Notes <span class="opt">(optional)</span></label>
                    <textarea name="notes"
                              class="fc @error('notes') is-invalid @enderror"
                              placeholder="e.g. Driver's fee included, subject to availability…">{{ old('notes', $fare?->notes) }}</textarea>
                    @error('notes') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                </div>

            {{-- Footer inside form --}}
            <div style="display:flex;justify-content:flex-end;align-items:center;gap:.75rem;
                        padding-top:1.25rem;border-top:1.5px solid #f3f4f6;margin-top:.5rem;">
                <a href="{{ route('admin.fares.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    {{ $edit ? '💾 Update Fare' : '➕ Add Vehicle Fare' }}
                </button>
            </div>

            </form>
        </div>
    </div>
</div>

@endsection