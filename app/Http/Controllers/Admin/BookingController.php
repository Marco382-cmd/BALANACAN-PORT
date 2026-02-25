<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['trip.route', 'passengers', 'vehicles'])
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_reference', 'like', "%{$s}%")
                  ->orWhere('contact_name', 'like', "%{$s}%")
                  ->orWhere('contact_email', 'like', "%{$s}%")
                  ->orWhere('contact_phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('travel_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('travel_date', '<=', $request->date_to);
        }

        if ($request->filled('route_id')) {
            $query->whereHas('trip', fn($q) => $q->where('route_id', $request->route_id));
        }

        $bookings = $query->paginate(20)->withQueryString();
        $routes   = Route::where('status', '!=', 'inactive')->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'routes'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['trip.route', 'passengers', 'vehicles.vehicleFare']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updatePayment(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|in:gcash,maya,visa,mastercard,cash',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method ?? $booking->payment_method,
        ]);

        // Auto-confirm booking when payment is marked paid
        if ($request->payment_status === 'paid' && $booking->booking_status === 'pending') {
            $booking->update(['booking_status' => 'confirmed']);
        }

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled,boarded',
        ]);

        $booking->update(['booking_status' => $request->booking_status]);

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $ref = $booking->booking_reference;

        $booking->passengers()->delete();
        $booking->vehicles()->delete();
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', "Booking {$ref} has been deleted.");
    }

    public function create(): View
    {
        $trips  = Trip::with('route')
            ->where('status', 'scheduled')
            ->orderBy('departure_time')
            ->get();

        $routes = Route::where('status', '!=', 'inactive')
            ->orderBy('name')
            ->get();

        return view('admin.bookings.create', compact('trips', 'routes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'trip_id'          => 'required|exists:trips,id',
            'travel_date'      => 'required|date|after_or_equal:today',
            'contact_name'     => 'required|string|max:150',
            'contact_email'    => 'required|email|max:180',
            'contact_phone'    => 'required|string|max:20',
            'total_passengers' => 'required|integer|min:1|max:500',
            'total_amount'     => 'required|numeric|min:0',
            'payment_method'   => 'nullable|in:gcash,maya,visa,mastercard,cash',
            'payment_status'   => 'required|in:pending,paid,failed,refunded',
            'booking_status'   => 'required|in:pending,confirmed,cancelled,boarded',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // booking_reference is auto-generated in the model's booted() method
        $booking = Booking::create($data);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', "Booking {$booking->booking_reference} created successfully.");
    }
}