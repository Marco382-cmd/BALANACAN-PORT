<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        // ── Dashboard statistics ────────────────────────────────
        $stats = [
            'total_bookings'      => Booking::count(),
            'today_bookings'      => Booking::whereDate('created_at', $today)->count(),
            'confirmed_bookings'  => Booking::where('booking_status', 'confirmed')->count(),
            'pending_bookings'    => Booking::where('booking_status', 'pending')->count(),
            'cancelled_bookings'  => Booking::where('booking_status', 'cancelled')->count(),
            'total_revenue'       => Booking::where('payment_status', 'paid')->sum('total_amount'),
            'today_revenue'       => Booking::where('payment_status', 'paid')
                                           ->whereDate('created_at', $today)
                                           ->sum('total_amount'),

            // ✅ FIXED: changed 'scheduled' → 'active' to match TripController status values
            // TripController only allows: active, inactive, canceled
            'active_trips'        => Trip::where('status', 'active')->count(),

            'total_routes'        => Route::where('status', '!=', 'inactive')->count(),
        ];

        // ── Recent bookings (latest 10) ─────────────────────────
        $recentBookings = Booking::with('trip.route')
            ->latest()
            ->take(10)
            ->get();

        // ── Upcoming trips (next 5 active) ──────────────────────
        // ✅ FIXED: changed 'scheduled' → 'active' here too
        // Also removed departure_time >= now() since departure_time is stored
        // as a TIME string (e.g. "07:00"), not a full datetime — comparing it
        // to Carbon::now() won't work correctly.
        $upcomingTrips = Trip::with('route')
            ->where('status', 'active')
            ->orderBy('departure_time')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'upcomingTrips'));
    }
}