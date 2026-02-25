<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $routes = Route::with(['passengerFares', 'vehicleFares', 'trips'])
            ->where('status', '!=', 'inactive')
            ->orderBy('name')
            ->get();

        // Find next upcoming departure from now
        $nextDeparture = Trip::with('route')
            ->where('status', 'scheduled')
            ->where('departure_time', '>=', Carbon::now())
            ->orderBy('departure_time')
            ->first();

        return view('home', compact('routes', 'nextDeparture'));
    }
}