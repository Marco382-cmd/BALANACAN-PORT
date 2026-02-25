<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\PassengerFare;
use App\Models\VehicleFare;
use Illuminate\Http\Request;

class FareController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────
    public function index()
    {
        $routes = Route::with(['passengerFares', 'vehicleFares'])
            ->orderBy('name')
            ->get();

        return view('admin.fares.index', compact('routes'));
    }

    // ── Passenger Fares ───────────────────────────────────────────────────
    public function passengerCreate(Request $request)
    {
        $routes = Route::orderBy('name')->get();
        $fare   = null;
        $edit   = false;
        $selectedRouteId = $request->route_id;
        return view('admin.fares.passenger-form', compact('routes', 'fare', 'edit', 'selectedRouteId'));
    }

    public function passengerStore(Request $request)
    {
        $validated = $request->validate([
            'route_id'     => 'required|exists:routes,id',
            'fare_type'    => 'required|in:regular,student,senior,children,pwd',
            'label'        => 'required|string|max:60',
            'amount'       => 'required|numeric|min:0',
            'discount_pct' => 'nullable|numeric|min:0|max:100',
            'notes'        => 'nullable|string',
        ]);

        $validated['discount_pct'] = $validated['discount_pct'] ?? 0;

        PassengerFare::create($validated);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Passenger fare added successfully.');
    }

    public function passengerEdit(PassengerFare $passengerFare)
    {
        $routes = Route::orderBy('name')->get();
        $fare   = $passengerFare;
        $edit   = true;
        $selectedRouteId = $fare->route_id;
        return view('admin.fares.passenger-form', compact('routes', 'fare', 'edit', 'selectedRouteId'));
    }

   public function updatePassengerFare(Request $request, PassengerFare $fare)
{
    // Validate input
    $data = $request->validate([
        'discount_pct' => 'nullable|numeric|min:0|max:100',
        'required_id'  => 'nullable|string|max:200',
        'notes'        => 'nullable|string|max:500',
    ]);

    // If discount_pct is null, set it to 0 (or any default you prefer)
    $data['discount_pct'] = $data['discount_pct'] ?? 0;

    // Update the fare
    $fare->update($data);

    return back()->with('success', 'Passenger fare updated successfully.');
}

    public function passengerDestroy(PassengerFare $passengerFare)
    {
        $passengerFare->delete();
        return redirect()->route('admin.fares.index')
            ->with('success', 'Passenger fare deleted.');
    }

    // ── Vehicle Fares ─────────────────────────────────────────────────────
    public function vehicleCreate(Request $request)
    {
        $routes = Route::orderBy('name')->get();
        $fare   = null;
        $edit   = false;
        $selectedRouteId = $request->route_id;
        return view('admin.fares.vehicle-form', compact('routes', 'fare', 'edit', 'selectedRouteId'));
    }

    public function vehicleStore(Request $request)
    {
        $validated = $request->validate([
            'route_id'         => 'required|exists:routes,id',
            'vehicle_type'     => 'required|in:bicycle,motorcycle,type2,type3,type4',
            'label'            => 'required|string|max:100',
            'size_description' => 'nullable|string|max:80',
            'fare_min'         => 'required|numeric|min:0',
            'fare_max'         => 'required|numeric|min:0|gte:fare_min',
            'notes'            => 'nullable|string',
        ]);

        VehicleFare::create($validated);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare added successfully.');
    }

    public function vehicleEdit(VehicleFare $vehicleFare)
    {
        $routes = Route::orderBy('name')->get();
        $fare   = $vehicleFare;
        $edit   = true;
        $selectedRouteId = $fare->route_id;
        return view('admin.fares.vehicle-form', compact('routes', 'fare', 'edit', 'selectedRouteId'));
    }

    public function vehicleUpdate(Request $request, VehicleFare $vehicleFare)
    {
        $validated = $request->validate([
            'route_id'         => 'required|exists:routes,id',
            'vehicle_type'     => 'required|in:bicycle,motorcycle,type2,type3,type4',
            'label'            => 'required|string|max:100',
            'size_description' => 'nullable|string|max:80',
            'fare_min'         => 'required|numeric|min:0',
            'fare_max'         => 'required|numeric|min:0|gte:fare_min',
            'notes'            => 'nullable|string',
        ]);

        $vehicleFare->update($validated);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare updated successfully.');
    }

    public function vehicleDestroy(VehicleFare $vehicleFare)
    {
        $vehicleFare->delete();
        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare deleted.');
    }
}