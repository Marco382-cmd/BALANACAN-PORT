@extends('admin.layouts.admin')

@section('title', $edit ?? false ? 'Edit Passenger Fare' : 'Add Passenger Fare')
@section('breadcrumb', 'Fares → Passenger → ' . ($edit ?? false ? 'Edit' : 'Create'))

@section('content')

<style>
    /* ── Page layout ── */
    .pfare-page {
        max-width: 680px;
        margin: 0 auto;
    }

    /* ── Page header ── */
    .pfare-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .pfare-page-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f2d4a;
        letter-spacing: -.02em;
    }
    .pfare-page-sub {
        font-size: .85rem;
        color: #6b7280;
        margin-top: .2rem;
    }
    .pfare-page-sub strong { color: #0f2d4a; }

    /* ── Card ── */
    .pfare-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(15,45,74,.07);
    }

    /* ── Banner ── */
    .pfare-banner {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%);
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    .pfare-banner::before {
        content: '🧍';
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4.5rem;
        opacity: .12;
        pointer-events: none;
    }
    .pfare-banner-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,.15);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .pfare-banner-text h2 {
        color: #fff;
        font-size: 1rem;
        font-weight: 800;
        margin: 0;
    }
    .pfare-banner-text p {
        color: rgba(255,255,255,.6);
        font-size: .8rem;
        margin: .2rem 0 0;
    }

    /* ── Form body ── */
    .pfare-form-body { padding: 2rem; }

    /* ── Error banner ── */
    .pfare-errors {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 10px;
        padding: .85rem 1rem;
        margin-bottom: 1.5rem;
        font-size: .83rem;
        color: #b91c1c;
    }

    /* ── Section label ── */
    .pfare-section-label {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #9ca3af;
        margin: 1.75rem 0 1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .pfare-section-label:first-of-type { margin-top: 0; }

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
        border-color: #065f46;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(6,95,70,.08);
    }
    .fc.is-invalid {
        border-color: #ef4444;
        background: #fff5f5;
    }
    textarea.fc { resize: vertical; min-height: 85px; }

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

    /* ── Fare type visual picker ── */
    .fare-type-picker {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: .5rem;
    }
    .ft-radio { display: none; }
    .ft-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .35rem;
        padding: .9rem .5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all .15s;
        background: #fafafa;
        text-align: center;
    }
    .ft-pill .fp-icon { font-size: 1.6rem; line-height: 1; }
    .ft-pill .fp-label {
        font-size: .68rem;
        font-weight: 700;
        color: #6b7280;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .ft-radio:checked + .ft-pill {
        border-color: #065f46;
        background: #ecfdf5;
    }
    .ft-radio:checked + .ft-pill .fp-label { color: #065f46; }
    .ft-pill:hover { border-color: #6ee7b7; background: #f0fdf4; }

    /* ── Amount prefix ── */
    .amount-wrap { position: relative; }
    .amount-prefix {
        position: absolute;
        left: .9rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: .88rem;
        font-weight: 700;
        color: #9ca3af;
        pointer-events: none;
    }
    .amount-wrap .fc { padding-left: 1.85rem; }

    /* ── Discount badge preview ── */
    .discount-preview {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin-top: .5rem;
        padding: .3rem .7rem;
        background: #dcfce7;
        color: #15803d;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        transition: all .2s;
    }
    .discount-preview.hidden { display: none; }

    /* ── Required ID info box ── */
    .id-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: .75rem 1rem;
        font-size: .8rem;
        color: #0369a1;
        margin-top: .5rem;
        display: none;
    }
    .id-info.show { display: flex; align-items: center; gap: .5rem; }

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
        display: inline-block;
    }
    .btn-cancel:hover { border-color: #9ca3af; color: #374151; }

    .btn-submit {
        padding: .65rem 1.5rem;
        border: none;
        border-radius: 10px;
        background: #065f46;
        color: #fff;
        font-size: .88rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .15s;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .btn-submit:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6,95,70,.3);
    }
    .btn-submit:active { transform: translateY(0); }

    @media (max-width: 520px) {
        .fare-type-picker { grid-template-columns: repeat(3, 1fr); }
        .fg-row { grid-template-columns: 1fr; }
        .pfare-form-body { padding: 1.25rem; }
    }
</style>

<div class="pfare-page">

    {{-- Page header --}}
    <div class="pfare-page-header">
        <div>
            <div class="pfare-page-title">
                {{ ($edit ?? false) ? '✏️ Edit Passenger Fare' : '🧍 Add Passenger Fare' }}
            </div>
            <div class="pfare-page-sub">
                Route: <strong>{{ $route->name }}</strong>
                &nbsp;·&nbsp;
            
            </div>
        </div>
        <a href="{{ route('admin.fares.index') }}" class="btn-cancel">← Back to Fares</a>
    </div>

    {{-- Card --}}
    <div class="pfare-card">

        {{-- Banner --}}
        <div class="pfare-banner">
            <div class="pfare-banner-icon">🧍</div>
            <div class="pfare-banner-text">
                <h2>{{ ($edit ?? false) ? 'Update Passenger Fare' : 'New Passenger Fare' }}</h2>
                <p>{{ $route->origin_name }} ⇄ {{ $route->destination_name }} &nbsp;·&nbsp;</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="pfare-form-body">

            @if($errors->any())
            <div class="pfare-errors">
                ⚠️ Please fix the highlighted errors before saving.
            </div>
            @endif

            <form method="POST"
                  action="{{ ($edit ?? false)
                    ? route('admin.fares.passenger.update', $fare->id)
                    : route('admin.fares.passenger.store', $route->id) }}">
                @csrf
                @if($edit ?? false) @method('PUT') @endif

                {{-- Fare Type Picker --}}
                <div class="pfare-section-label">Fare Type</div>
                <div class="fg">
                    <label>Select Fare Type <span class="req">*</span></label>
                    <div class="fare-type-picker">
                        @foreach([
                            ['value'=>'regular',  'icon'=>'🧑', 'label'=>'Regular'],
                            ['value'=>'student',  'icon'=>'🎓', 'label'=>'Student'],
                            ['value'=>'senior',   'icon'=>'👴', 'label'=>'Senior'],
                            ['value'=>'pwd',      'icon'=>'♿', 'label'=>'PWD'],
                            ['value'=>'children', 'icon'=>'👦', 'label'=>'Children'],
                        ] as $ft)
                        <div>
                            <input type="radio" name="fare_type"
                                   id="ft_{{ $ft['value'] }}"
                                   value="{{ $ft['value'] }}"
                                   class="ft-radio"
                                   {{ old('fare_type', ($fare ?? null)?->fare_type ?? 'regular') === $ft['value'] ? 'checked' : '' }}>
                            <label for="ft_{{ $ft['value'] }}" class="ft-pill">
                                <span class="fp-icon">{{ $ft['icon'] }}</span>
                                <span class="fp-label">{{ $ft['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('fare_type')
                        <div class="err-msg">⚠ {{ $message }}</div>
                    @enderror

                    {{-- ID required info hints --}}
                    <div class="id-info show" id="id-hint-regular">ℹ️ No ID required for Regular fare.</div>
                    <div class="id-info" id="id-hint-student">🪪 Valid School ID or Student ID required at port.</div>
                    <div class="id-info" id="id-hint-senior">🪪 Senior Citizen ID (60+) required at port.</div>
                    <div class="id-info" id="id-hint-pwd">🪪 PWD ID issued by NCDA required at port.</div>
                    <div class="id-info" id="id-hint-children">ℹ️ No ID required for Children fare.</div>
                </div>

                {{-- Label --}}
                <div class="pfare-section-label">Details</div>
                <div class="fg">
                    <label>Display Label <span class="opt">(optional)</span></label>
                    <input type="text" name="label"
                           class="fc @error('label') is-invalid @enderror"
                           value="{{ old('label', ($fare ?? null)?->label) }}"
                           placeholder="e.g. Regular Adult, Student Fare…">
                    @error('label') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                    <p class="hint">If left blank, the fare type name is used.</p>
                </div>

                {{-- Required ID --}}
                <div class="fg">
                    <label>Required ID <span class="opt">(optional)</span></label>
                    <input type="text" name="required_id"
                           class="fc @error('required_id') is-invalid @enderror"
                           value="{{ old('required_id', ($fare ?? null)?->required_id) }}"
                           placeholder="e.g. Valid School ID, Senior Citizen ID…">
                    @error('required_id') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                    <p class="hint">Leave blank for Regular fare.</p>
                </div>

                {{-- Amount & Discount --}}
                <div class="pfare-section-label">Pricing</div>
                <div class="fg-row">
                    <div class="fg" style="margin:0;">
                        <label>Amount (₱) <span class="req">*</span></label>
                        <div class="amount-wrap">
                            <span class="amount-prefix">₱</span>
                            <input type="number" name="amount" id="amountInput"
                                   step="0.01" min="0"
                                   class="fc @error('amount') is-invalid @enderror"
                                   value="{{ old('amount', ($fare ?? null)?->amount) }}"
                                   placeholder="0.00">
                        </div>
                        @error('amount') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                    </div>
                    <div class="fg" style="margin:0;">
                        <label>Discount % <span class="opt">(optional)</span></label>
                        <input type="number" name="discount_pct" id="discountInput"
                               step="0.01" min="0" max="100"
                               class="fc @error('discount_pct') is-invalid @enderror"
                               value="{{ old('discount_pct', ($fare ?? null)?->discount_pct ?? 0) }}"
                               placeholder="0">
                        @error('discount_pct') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                        <div class="discount-preview hidden" id="discountPreview">
                            <span>🏷</span> <span id="discountText"></span> off regular fare
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="pfare-section-label" style="margin-top:1.5rem;">Additional Info</div>
                <div class="fg">
                    <label>Notes <span class="opt">(optional)</span></label>
                    <textarea name="notes"
                              class="fc @error('notes') is-invalid @enderror"
                              placeholder="Any conditions or additional information…">{{ old('notes', ($fare ?? null)?->notes) }}</textarea>
                    @error('notes') <div class="err-msg">⚠ {{ $message }}</div> @enderror
                </div>

                {{-- Footer --}}
                <div style="display:flex;justify-content:flex-end;align-items:center;gap:.75rem;
                            padding-top:1.25rem;border-top:1.5px solid #f3f4f6;margin-top:.5rem;">
                    <a href="{{ route('admin.fares.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        {{ ($edit ?? false) ? '💾 Update Fare' : '➕ Add Passenger Fare' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // ── ID hint toggler ────────────────────────────────────────
    const hints = ['regular','student','senior','pwd','children'];
    function updateHint(val) {
        hints.forEach(h => {
            const el = document.getElementById('id-hint-' + h);
            if (el) el.classList.toggle('show', h === val);
        });
    }
    document.querySelectorAll('input[name="fare_type"]').forEach(r => {
        r.addEventListener('change', () => updateHint(r.value));
    });
    // Init on load
    const checked = document.querySelector('input[name="fare_type"]:checked');
    if (checked) updateHint(checked.value);

    // ── Discount preview ────────────────────────────────────────
    const discountInput   = document.getElementById('discountInput');
    const discountPreview = document.getElementById('discountPreview');
    const discountText    = document.getElementById('discountText');
    function updateDiscount() {
        const val = parseFloat(discountInput.value);
        if (val > 0) {
            discountText.textContent = val + '%';
            discountPreview.classList.remove('hidden');
        } else {
            discountPreview.classList.add('hidden');
        }
    }
    discountInput?.addEventListener('input', updateDiscount);
    updateDiscount();
</script>

@endsection