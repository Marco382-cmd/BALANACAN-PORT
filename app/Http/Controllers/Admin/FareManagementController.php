<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\PassengerFare;
use App\Models\VehicleFare;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FareManagementController extends Controller
{
    public function index(): View
    {
        $routes = Route::with(['passengerFares', 'vehicleFares'])
            ->where('status', '!=', 'inactive')
            ->orderBy('name')
            ->get();

        return view('admin.fares.index', compact('routes'));
    }

    // ── Passenger Fares ──────────────────────────────────────────────────────

    public function createPassengerFare(Route $route): View
    {
        return view('admin.fares.passenger.form', [
            'route' => $route,
            'fare'  => null,
            'edit'  => false,
        ]);
    }

    public function storePassengerFare(Request $request, Route $route): RedirectResponse
    {
        $data = $request->validate([
            'fare_type'    => 'required|in:regular,student,senior,pwd,children',
            'label'        => 'nullable|string|max:100',
            'amount'       => 'required|numeric|min:0',
            'discount_pct' => 'nullable|numeric|min:0|max:100',
            'required_id'  => 'nullable|string|max:200',
            'notes'        => 'nullable|string|max:500',
        ]);

        $data['discount_pct'] = $data['discount_pct'] ?? 0;

        $route->passengerFares()->create($data);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Passenger fare added successfully.');
    }

    public function editPassengerFare(PassengerFare $fare): View
    {
        return view('admin.fares.passenger.form', [
            'route' => $fare->route,
            'fare'  => $fare,
            'edit'  => true,
        ]);
    }

   public function updatePassengerFare(Request $request, PassengerFare $fare): RedirectResponse
{
    $data = $request->validate([
        'fare_type'    => 'required|in:regular,student,senior,pwd,children',
        'label'        => 'nullable|string|max:100',
        'amount'       => 'required|numeric|min:0',
        'discount_pct' => 'nullable|numeric|min:0|max:100',
        'notes'        => 'nullable|string|max:500',
    ]);

    // Ensure discount_pct is never null
    $data['discount_pct'] = $data['discount_pct'] ?? 0;

    $fare->update($data);

    return redirect()->route('admin.fares.index')
        ->with('success', 'Passenger fare updated successfully.');
}

    public function destroyPassengerFare(PassengerFare $fare): RedirectResponse
    {
        $fare->delete();

        return redirect()->route('admin.fares.index')
            ->with('success', 'Passenger fare deleted.');
    }

    // ── Vehicle Fares ────────────────────────────────────────────────────────

    public function createVehicleFare(Route $route): View
    {
        return view('admin.fares.vehicle.form', [
            'route' => $route,
            'fare'  => null,
            'edit'  => false,
        ]);
    }

    public function storeVehicleFare(Request $request, Route $route): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_type'     => 'required|string|max:50',
            'label'            => 'nullable|string|max:100',
            'size_description' => 'nullable|string|max:100',
            'fare_min'         => 'required|numeric|min:0',
            'fare_max'         => 'nullable|numeric|min:0|gte:fare_min',
            'notes'            => 'nullable|string|max:500',
        ]);

        $route->vehicleFares()->create($data);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare added successfully.');
    }

    public function editVehicleFare(VehicleFare $fare): View
    {
        return view('admin.fares.vehicle.form', [
            'route' => $fare->route,
            'fare'  => $fare,
            'edit'  => true,
        ]);
    }

    public function updateVehicleFare(Request $request, VehicleFare $fare): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_type'     => 'required|string|max:50',
            'label'            => 'nullable|string|max:100',
            'size_description' => 'nullable|string|max:100',
            'fare_min'         => 'required|numeric|min:0',
            'fare_max'         => 'nullable|numeric|min:0|gte:fare_min',
            'notes'            => 'nullable|string|max:500',
        ]);

        $fare->update($data);

        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare updated successfully.');
    }

    public function destroyVehicleFare(VehicleFare $fare): RedirectResponse
    {
        $fare->delete();

        return redirect()->route('admin.fares.index')
            ->with('success', 'Vehicle fare deleted.');
    }
}