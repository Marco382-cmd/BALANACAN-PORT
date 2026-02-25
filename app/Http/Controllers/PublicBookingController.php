<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\Route;
use App\Models\PassengerFare;
use App\Models\VehicleFare;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    // ── Schedules page ────────────────────────────────────────────────────
    public function schedules()
    {
        $routes = Route::with(['trips' => function ($q) {
            $q->whereIn('status', ['active', 'scheduled'])
              ->orderBy('departure_time');
        }])->where('status', '!=', 'inactive')->get();

        return view('schedules', compact('routes'));
    }

    // ── Book form (GET /book) ─────────────────────────────────────────────
    // Named index() — NOT create() — to avoid BadMethodCallException
    public function index()
    {
        $routes = Route::with(['passengerFares', 'vehicleFares'])
            ->where('status', '!=', 'inactive')
            ->orderBy('name')
            ->get();

        $trips = Trip::with('route')
            ->whereIn('status', ['active', 'scheduled'])
            ->orderBy('departure_time')
            ->get();

        // Prepare JS-safe data — avoids Blade ParseError with inline fn() inside @json()
        $routesJs = $routes->keyBy('id')->map(fn($r) => [
            'passengerFares' => $r->passengerFares->keyBy('fare_type')->map(fn($f) => $f->amount),
            'vehicleFares'   => $r->vehicleFares->map(fn($f) => [
                'id'    => $f->id,
                'label' => $f->label,
                'min'   => $f->fare_min,
                'max'   => $f->fare_max,
            ])->values(),
        ]);

        $tripsJs = $trips->map(fn($t) => [
            'id'       => $t->id,
            'route_id' => $t->route_id,
        ])->keyBy('id');

        return view('book', compact('routes', 'trips', 'routesJs', 'tripsJs'));
    }

    // ── Store booking (POST /book) ────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'trip_id'                        => 'required|exists:trips,id',
            'travel_date'                    => 'required|date|after_or_equal:today',
            'contact_name'                   => 'required|string|max:150',
            'contact_email'                  => 'required|email|max:180',
            'contact_phone'                  => 'required|string|max:20',
            'payment_method'                 => 'required|in:gcash,maya,visa,mastercard,cash',

            'passengers'                     => 'required|array|min:1',
            'passengers.*.first_name'        => 'required|string|max:100',
            'passengers.*.last_name'         => 'required|string|max:100',
            'passengers.*.fare_type'         => 'required|in:regular,student,senior,children,pwd',
            'passengers.*.id_type'           => 'nullable|string|max:80',
            'passengers.*.id_number'         => 'nullable|string|max:80',

            'vehicles'                       => 'nullable|array',
            'vehicles.*.vehicle_fare_id'     => 'required_with:vehicles|exists:vehicle_fares,id',
            'vehicles.*.plate_number'        => 'required_with:vehicles|string|max:20',
            'vehicles.*.vehicle_description' => 'nullable|string|max:150',
        ]);

        $trip = Trip::with('route')->findOrFail($request->trip_id);

        // ── Calculate totals ──────────────────────────────────────────────
        $totalAmount   = 0;
        $passengerRows = [];

        foreach ($request->passengers as $p) {
            $fare    = PassengerFare::where('route_id', $trip->route_id)
                           ->where('fare_type', $p['fare_type'])
                           ->first();
            $amount  = $fare ? (float) $fare->amount : 0;
            $totalAmount += $amount;

            $passengerRows[] = [
                'first_name' => $p['first_name'],
                'last_name'  => $p['last_name'],
                'fare_type'  => $p['fare_type'],
                'id_type'    => $p['id_type']   ?? null,
                'id_number'  => $p['id_number'] ?? null,
                'amount'     => $amount,
            ];
        }

        $vehicleRows = [];
        foreach ($request->vehicles ?? [] as $v) {
            $vFare        = VehicleFare::findOrFail($v['vehicle_fare_id']);
            $amount       = (float) $vFare->fare_min;
            $totalAmount += $amount;

            $vehicleRows[] = [
                'vehicle_fare_id'     => $v['vehicle_fare_id'],
                'plate_number'        => $v['plate_number'],
                'vehicle_description' => $v['vehicle_description'] ?? null,
                'amount'              => $amount,
            ];
        }

        // ── Create booking record ─────────────────────────────────────────
        $booking = Booking::create([
            'trip_id'          => $trip->id,
            'travel_date'      => $request->travel_date,
            'contact_name'     => $request->contact_name,
            'contact_email'    => $request->contact_email,
            'contact_phone'    => $request->contact_phone,
            'total_passengers' => count($passengerRows),
            'total_amount'     => $totalAmount,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
            'booking_status'   => 'pending',
        ]);

        foreach ($passengerRows as $row) {
            $booking->passengers()->create($row);
        }

        foreach ($vehicleRows as $row) {
            $booking->vehicles()->create($row);
        }

        return redirect()
            ->route('booking.confirmation', $booking->booking_reference)
            ->with('success', 'Booking created! Please save your reference number.');
    }

    // ── Confirmation page ─────────────────────────────────────────────────
    public function confirmation(string $ref)
    {
        $booking = Booking::with(['trip.route', 'passengers', 'vehicles.vehicleFare'])
            ->where('booking_reference', $ref)
            ->firstOrFail();

        return view('booking-confirmation', compact('booking'));
    }

    // ── My Bookings lookup ────────────────────────────────────────────────
    public function lookup(Request $request)
    {
        $booking = null;
        $error   = null;

        if ($request->filled('ref') && $request->filled('email')) {
            $booking = Booking::with(['trip.route', 'passengers', 'vehicles.vehicleFare'])
                ->where('booking_reference', strtoupper(trim($request->ref)))
                ->where('contact_email', strtolower(trim($request->email)))
                ->first();

            if (! $booking) {
                $error = 'No booking found with that reference number and email address.';
            }
        }

        return view('bookings', compact('booking', 'error'));
    }
}