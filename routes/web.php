<?php

// routes/web.php — COMPLETE (public + admin)
// ─────────────────────────────────────────────────────────────────────────────

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FareController;
use App\Http\Controllers\PublicBookingController;

// Admin controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\TripController as AdminTripController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\FareManagementController;
use App\Http\Controllers\Admin\ReportController;

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// PUBLIC ROUTES
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Fare guide (public read-only view)
Route::get('/fares',         [FareController::class, 'index'])->name('fares');
Route::get('/fares/{route}', [FareController::class, 'byRoute'])->name('fares.route');

// Schedules
Route::get('/schedules', [PublicBookingController::class, 'schedules'])->name('schedules');

// Booking — uses index() not create() to avoid BadMethodCallException
Route::get('/book',  [PublicBookingController::class, 'index'])->name('book');
Route::post('/book', [PublicBookingController::class, 'store'])->name('book.store');

// Booking confirmation
Route::get('/booking/confirmation/{ref}', [PublicBookingController::class, 'confirmation'])
     ->name('booking.confirmation');

// My Bookings lookup
Route::get('/bookings', [PublicBookingController::class, 'lookup'])->name('bookings');

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->group(function () {

    // ── Auth (no middleware) ─────────────────────────────────────────────────
    Route::get('/login',   [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // ── Protected admin area ─────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Ferry Routes CRUD
        Route::resource('routes', AdminRouteController::class);

        // Trips / Schedules
        Route::resource('trips', AdminTripController::class)->except(['show']);
        Route::patch('trips/{trip}/status', [AdminTripController::class, 'updateStatus'])
             ->name('trips.status');

        // ── Bookings ─────────────────────────────────────────────────────────
        // Specific named routes BEFORE wildcard {booking} to avoid
        // Laravel matching 'create' as a booking ID.
        Route::get('bookings',                     [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create',              [AdminBookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings',                    [AdminBookingController::class, 'store'])->name('bookings.store');
        Route::get('bookings/{booking}',           [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::delete('bookings/{booking}',        [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
        Route::patch('bookings/{booking}/payment', [AdminBookingController::class, 'updatePayment'])->name('bookings.payment');
        Route::patch('bookings/{booking}/status',  [AdminBookingController::class, 'updateStatus'])->name('bookings.status');

        // ── Fare Management ───────────────────────────────────────────────────
        // All specific/static fare sub-routes MUST come BEFORE the
        // generic 'fares' index — Laravel matches top-to-bottom.

        // Passenger fares
        Route::get('fares/routes/{route}/passenger/create', [FareManagementController::class, 'createPassengerFare'])->name('fares.passenger.create');
        Route::post('fares/routes/{route}/passenger',       [FareManagementController::class, 'storePassengerFare'])->name('fares.passenger.store');
        Route::get('fares/passenger/{fare}/edit',           [FareManagementController::class, 'editPassengerFare'])->name('fares.passenger.edit');
        Route::put('fares/passenger/{fare}',                [FareManagementController::class, 'updatePassengerFare'])->name('fares.passenger.update');
        Route::delete('fares/passenger/{fare}',             [FareManagementController::class, 'destroyPassengerFare'])->name('fares.passenger.destroy');

        // Vehicle fares
        Route::get('fares/routes/{route}/vehicle/create',   [FareManagementController::class, 'createVehicleFare'])->name('fares.vehicle.create');
        Route::post('fares/routes/{route}/vehicle',         [FareManagementController::class, 'storeVehicleFare'])->name('fares.vehicle.store');
        Route::get('fares/vehicle/{fare}/edit',             [FareManagementController::class, 'editVehicleFare'])->name('fares.vehicle.edit');
        Route::put('fares/vehicle/{fare}',                  [FareManagementController::class, 'updateVehicleFare'])->name('fares.vehicle.update');
        Route::delete('fares/vehicle/{fare}',               [FareManagementController::class, 'destroyVehicleFare'])->name('fares.vehicle.destroy');

        // Fares index — MUST be AFTER all specific fare sub-routes
        Route::get('fares', [FareManagementController::class, 'index'])->name('fares.index');

        // Reports
        Route::get('reports',        [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    }); // end middleware('admin')

}); // end prefix('admin')