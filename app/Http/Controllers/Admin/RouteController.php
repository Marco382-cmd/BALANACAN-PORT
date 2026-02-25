<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['passengerFares', 'vehicleFares', 'trips'])
            ->withCount('trips')
            ->orderBy('status')
            ->get();

        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.form', ['route' => new Route(), 'edit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:100',
            'origin_code'          => 'required|string|size:3',
            'origin_name'          => 'required|string|max:100',
            'origin_location'      => 'required|string|max:150',
            'destination_code'     => 'required|string|size:3',
            'destination_name'     => 'required|string|max:100',
            'destination_location' => 'required|string|max:150',
            'duration_minutes'     => 'required|integer|min:1',
            'status'               => 'required|in:active,seasonal,inactive',
            'trips_per_day'        => 'required|integer|min:1',
        ]);

        // Force uppercase for port codes
        $data['origin_code']      = strtoupper($data['origin_code']);
        $data['destination_code'] = strtoupper($data['destination_code']);

        $route = Route::create($data);

        return redirect()->route('admin.routes.show', $route)
            ->with('success', "Route \"{$route->name}\" created successfully.");
    }

    public function show(Route $route)
    {
        $route->load(['passengerFares', 'vehicleFares', 'trips']);
        return view('admin.routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        return view('admin.routes.form', ['route' => $route, 'edit' => true]);
    }

    public function update(Request $request, Route $route)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:100',
            'origin_code'          => 'required|string|size:3',
            'origin_name'          => 'required|string|max:100',
            'origin_location'      => 'required|string|max:150',
            'destination_code'     => 'required|string|size:3',
            'destination_name'     => 'required|string|max:100',
            'destination_location' => 'required|string|max:150',
            'duration_minutes'     => 'required|integer|min:1',
            'status'               => 'required|in:active,seasonal,inactive',
            'trips_per_day'        => 'required|integer|min:1',
        ]);

        // Force uppercase for port codes
        $data['origin_code']      = strtoupper($data['origin_code']);
        $data['destination_code'] = strtoupper($data['destination_code']);

        $route->update($data);

        return redirect()->route('admin.routes.show', $route)
            ->with('success', "Route updated successfully.");
    }

    public function destroy(Route $route)
    {
        $name = $route->name;
        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', "Route \"{$name}\" deleted.");
    }
}