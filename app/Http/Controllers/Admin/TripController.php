<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Route;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with('route')->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        $routes = Route::all();
        $trip   = null;
        $edit   = false;
        return view('admin.trips.form', compact('routes', 'trip', 'edit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id'                   => 'required|exists:routes,id',
            'departure_time'             => 'required',           // stored as TIME e.g. 07:00
            'vessel_name'                => 'nullable|string|max:100',
            'available_passenger_slots'  => 'required|integer|min:0',
            'available_vehicle_slots'    => 'required|integer|min:0',
            'status'                     => 'required|in:active,inactive,canceled',
            'notes'                      => 'nullable|string',
        ]);

        Trip::create($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip created successfully!');
    }

    public function edit(Trip $trip)
    {
        $routes = Route::all();
        $edit   = true;
        return view('admin.trips.form', compact('routes', 'trip', 'edit'));
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'route_id'                   => 'required|exists:routes,id',
            'departure_time'             => 'required',
            'vessel_name'                => 'nullable|string|max:100',
            'available_passenger_slots'  => 'required|integer|min:0',
            'available_vehicle_slots'    => 'required|integer|min:0',
            'status'                     => 'required|in:active,inactive,canceled',
            'notes'                      => 'nullable|string',
        ]);

        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully!');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')->with('success', 'Trip deleted!');
    }

    public function updateStatus(Request $request, Trip $trip)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,canceled',
        ]);

        $trip->status = $request->status;
        $trip->save();

        return redirect()->back()->with('success', 'Trip status updated!');
    }
}