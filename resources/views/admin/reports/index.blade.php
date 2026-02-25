@extends('admin.layouts.admin')

@section('title', 'Reports')
@section('breadcrumb', 'Analytics → <strong>Reports</strong>')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div class="page-title">📈 Revenue Reports</div>
        <div class="page-sub">
            {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} –
            {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
        </div>
    </div>
    <a href="{{ route('admin.reports.export', request()->query()) }}" class="btn btn-success">
        ⬇️ Export CSV
    </a>
</div>

{{-- Date filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <form method="GET" class="filter-bar">
        <div class="form-group">
            <label class="form-label">From</label>
            <input type="date" name="date_from" class="form-control"
                   value="{{ \Carbon\Carbon::parse($dateFrom)->toDateString() }}">
        </div>
        <div class="form-group">
            <label class="form-label">To</label>
            <input type="date" name="date_to" class="form-control"
                   value="{{ \Carbon\Carbon::parse($dateTo)->toDateString() }}">
        </div>
        <div style="display:flex;gap:.5rem;align-items:flex-end;">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Reset</a>
        </div>

        {{-- Quick ranges --}}
        <div style="display:flex;gap:.4rem;align-items:flex-end;margin-left:auto;">
            @foreach(['7'=>'7 days','30'=>'30 days','90'=>'90 days'] as $days => $label)
            <a href="{{ route('admin.reports.index', ['date_from' => now()->subDays($days-1)->toDateString(), 'date_to' => now()->toDateString()]) }}"
               class="btn btn-secondary btn-sm">{{ $label }}</a>
            @endforeach
        </div>
    </form>
</div>

{{-- Summary stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-icon stat-icon--green">💰</div>
        <div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₱{{ number_format($revenue->total ?? 0, 0) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon--blue">🎫</div>
        <div>
            <div class="stat-label">Paid Bookings</div>
            <div class="stat-value">{{ number_format($revenue->bookings ?? 0) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon--teal">🧍</div>
        <div>
            <div class="stat-label">Total Passengers</div>
            <div class="stat-value">{{ number_format($revenue->passengers ?? 0) }}</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

    {{-- Revenue by Route --}}
    <div class="card">
        <div class="card-header"><div class="card-title">🗺 Revenue by Route</div></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Route</th><th>Bookings</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    @forelse($revenueByRoute as $row)
                    <tr>
                        <td style="font-weight:600;">{{ $row->route_name }}</td>
                        <td>{{ number_format($row->count) }}</td>
                        <td style="font-weight:800;color:#0f2d4a;">₱{{ number_format($row->total, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#adb5bd;padding:1.5rem;">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Fare type breakdown --}}
    <div class="card">
        <div class="card-header"><div class="card-title">🧍 Fare Type Breakdown</div></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Type</th><th>Count</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    @forelse($fareBreakdown as $row)
                    <tr>
                        <td><span class="badge badge-{{ $row->fare_type }}">{{ ucfirst($row->fare_type) }}</span></td>
                        <td>{{ number_format($row->count) }}</td>
                        <td style="font-weight:800;color:#0f2d4a;">₱{{ number_format($row->total, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#adb5bd;padding:1.5rem;">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Daily trend --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">📅 Daily Revenue Trend</div></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Date</th><th>Bookings</th><th>Revenue</th><th>Bar</th></tr>
            </thead>
            <tbody>
                @php $maxDay = $dailyRevenue->max('total') ?: 1; @endphp
                @forelse($dailyRevenue as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y (D)') }}</td>
                    <td>{{ $day->bookings }}</td>
                    <td style="font-weight:800;color:#0f2d4a;">₱{{ number_format($day->total, 0) }}</td>
                    <td style="width:40%;">
                        <div style="background:#dbeafe;border-radius:3px;height:14px;overflow:hidden;">
                            <div style="background:linear-gradient(to right,#1d4ed8,#60a5fa);height:100%;
                                        width:{{ round(($day->total/$maxDay)*100) }}%;border-radius:3px;">
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#adb5bd;padding:1.5rem;">No revenue data for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Payment methods --}}
<div class="card">
    <div class="card-header"><div class="card-title">💳 Payment Methods</div></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Method</th><th>Transactions</th><th>Revenue</th></tr>
            </thead>
            <tbody>
                @forelse($paymentMethods as $pm)
                @php $icons = ['gcash'=>'📱','maya'=>'💚','visa'=>'💳','mastercard'=>'💳','cash'=>'💵']; @endphp
                <tr>
                    <td>{{ $icons[$pm->payment_method] ?? '💳' }} <strong>{{ ucfirst($pm->payment_method) }}</strong></td>
                    <td>{{ number_format($pm->count) }}</td>
                    <td style="font-weight:800;color:#0f2d4a;">₱{{ number_format($pm->total, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:#adb5bd;padding:1.5rem;">No data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection