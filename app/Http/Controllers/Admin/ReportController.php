<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::now()->subDays(29)->startOfDay();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        // Revenue summary
        $revenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('SUM(total_amount) as total, COUNT(*) as bookings, SUM(total_passengers) as passengers')
            ->first();

        // Revenue by route
        $revenueByRoute = Booking::where('payment_status', 'paid')
            ->whereBetween('bookings.created_at', [$dateFrom, $dateTo])
            ->join('trips', 'bookings.trip_id', '=', 'trips.id')
            ->join('routes', 'trips.route_id', '=', 'routes.id')
            ->selectRaw('routes.name as route_name, SUM(bookings.total_amount) as total, COUNT(bookings.id) as count')
            ->groupBy('routes.id', 'routes.name')
            ->orderByDesc('total')
            ->get();

        // Fare type breakdown
        $fareBreakdown = BookingPassenger::join('bookings', 'booking_passengers.booking_id', '=', 'bookings.id')
            ->where('bookings.payment_status', 'paid')
            ->whereBetween('bookings.created_at', [$dateFrom, $dateTo])
            ->selectRaw('fare_type, COUNT(*) as count, SUM(booking_passengers.amount) as total')
            ->groupBy('fare_type')
            ->get();

        // Daily revenue trend
        $dailyRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method breakdown
        $paymentMethods = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $routes = Route::orderBy('name')->get();

        return view('admin.reports.index', compact(
            'revenue', 'revenueByRoute', 'fareBreakdown',
            'dailyRevenue', 'paymentMethods', 'routes',
            'dateFrom', 'dateTo'
        ));
    }

    public function exportCsv(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : Carbon::now()->subDays(29)->toDateString();
        $dateTo   = $request->filled('date_to')   ? $request->date_to   : Carbon::today()->toDateString();

        $bookings = Booking::with(['trip.route', 'passengers'])
            ->where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->orderBy('created_at')
            ->get();

        $filename = "bookings_{$dateFrom}_to_{$dateTo}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference', 'Route', 'Travel Date', 'Contact Name',
                'Email', 'Phone', 'Passengers', 'Total Amount',
                'Payment Method', 'Payment Status', 'Booking Status', 'Created At',
            ]);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->booking_reference,
                    $b->trip->route->name ?? '—',
                    $b->travel_date->toDateString(),
                    $b->contact_name,
                    $b->contact_email,
                    $b->contact_phone,
                    $b->total_passengers,
                    $b->total_amount,
                    $b->payment_method,
                    $b->payment_status,
                    $b->booking_status,
                    $b->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}