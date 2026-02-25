<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Trip;
use App\Models\PassengerFare;
use App\Models\VehicleFare;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Route 1: Balanacan ↔ Lucena ──────────────────────────────────────
        $route1 = Route::create([
            'name'                  => 'Balanacan – Lucena',
            'origin_code'           => 'BAL',
            'origin_name'           => 'Balanacan Port',
            'origin_location'       => 'Mogpog, Marinduque',
            'destination_code'      => 'LUC',
            'destination_name'      => 'Dalahican Port',
            'destination_location'  => 'Lucena City, Quezon',
            'duration_minutes'      => 210,
            'trips_per_day'         => 4,
            'status'                => 'active',
        ]);

        // ── Route 2: Balanacan ↔ Batangas ────────────────────────────────────
        $route2 = Route::create([
            'name'                  => 'Balanacan – Batangas',
            'origin_code'           => 'BAL',
            'origin_name'           => 'Balanacan Port',
            'origin_location'       => 'Mogpog, Marinduque',
            'destination_code'      => 'BTG',
            'destination_name'      => 'Batangas Port',
            'destination_location'  => 'Batangas City',
            'duration_minutes'      => 180,
            'trips_per_day'         => 2,
            'status'                => 'seasonal',
        ]);

        // ── Passenger Fares — Route 1 ─────────────────────────────────────────
        $passengerFares1 = [
            ['fare_type' => 'regular',  'label' => 'Regular Adult',   'amount' => 470.00, 'discount_pct' => 0,  'required_id' => 'No ID required'],
            ['fare_type' => 'student',  'label' => 'Student',         'amount' => 400.00, 'discount_pct' => 15, 'required_id' => 'School ID required'],
            ['fare_type' => 'senior',   'label' => 'Senior Citizen',  'amount' => 335.00, 'discount_pct' => 20, 'required_id' => 'Senior Citizen ID required'],
            ['fare_type' => 'pwd',      'label' => 'PWD',             'amount' => 376.00, 'discount_pct' => 20, 'required_id' => 'PWD ID required'],
            ['fare_type' => 'children', 'label' => 'Children (below 12)', 'amount' => 235.00, 'discount_pct' => 50, 'required_id' => 'No ID required'],
        ];
        foreach ($passengerFares1 as $fare) {
            $route1->passengerFares()->create($fare);
        }

        // ── Passenger Fares — Route 2 ─────────────────────────────────────────
        $passengerFares2 = [
            ['fare_type' => 'regular',  'label' => 'Regular Adult',   'amount' => 520.00, 'discount_pct' => 0,  'required_id' => 'No ID required'],
            ['fare_type' => 'student',  'label' => 'Student',         'amount' => 442.00, 'discount_pct' => 15, 'required_id' => 'School ID required'],
            ['fare_type' => 'senior',   'label' => 'Senior Citizen',  'amount' => 416.00, 'discount_pct' => 20, 'required_id' => 'Senior Citizen ID required'],
            ['fare_type' => 'pwd',      'label' => 'PWD',             'amount' => 416.00, 'discount_pct' => 20, 'required_id' => 'PWD ID required'],
            ['fare_type' => 'children', 'label' => 'Children (below 12)', 'amount' => 260.00, 'discount_pct' => 50, 'required_id' => 'No ID required'],
        ];
        foreach ($passengerFares2 as $fare) {
            $route2->passengerFares()->create($fare);
        }

        // ── Vehicle Fares — Route 1 ───────────────────────────────────────────
        $vehicleFares1 = [
            ['vehicle_type' => 'bicycle',    'label' => 'Bicycle',        'size_description' => null,           'fare_min' => 200,  'fare_max' => 200],
            ['vehicle_type' => 'motorcycle', 'label' => 'Motorcycle',     'size_description' => 'Standard',     'fare_min' => 650,  'fare_max' => 850],
            ['vehicle_type' => 'car',        'label' => 'Car / Sedan',    'size_description' => 'Up to 4.5m',   'fare_min' => 1800, 'fare_max' => 2200],
            ['vehicle_type' => 'suv',        'label' => 'SUV / Pick-up',  'size_description' => '4.5m – 5.5m', 'fare_min' => 2200, 'fare_max' => 2800],
            ['vehicle_type' => 'van',        'label' => 'Van / Minibus',  'size_description' => '5.5m – 7m',   'fare_min' => 2800, 'fare_max' => 3500],
            ['vehicle_type' => 'truck',      'label' => 'Truck / Cargo',  'size_description' => '7m and above', 'fare_min' => 4500, 'fare_max' => 8000],
        ];
        foreach ($vehicleFares1 as $fare) {
            $route1->vehicleFares()->create($fare);
        }

        // ── Vehicle Fares — Route 2 ───────────────────────────────────────────
        $vehicleFares2 = [
            ['vehicle_type' => 'motorcycle', 'label' => 'Motorcycle',    'size_description' => 'Standard',     'fare_min' => 750,  'fare_max' => 950],
            ['vehicle_type' => 'car',        'label' => 'Car / Sedan',   'size_description' => 'Up to 4.5m',   'fare_min' => 2000, 'fare_max' => 2400],
            ['vehicle_type' => 'suv',        'label' => 'SUV / Pick-up', 'size_description' => '4.5m – 5.5m', 'fare_min' => 2500, 'fare_max' => 3100],
            ['vehicle_type' => 'truck',      'label' => 'Truck / Cargo', 'size_description' => '7m and above', 'fare_min' => 5000, 'fare_max' => 9000],
        ];
        foreach ($vehicleFares2 as $fare) {
            $route2->vehicleFares()->create($fare);
        }

        // ── Trips — Route 1 (today and tomorrow) ─────────────────────────────
        $departureTimes1 = ['06:00', '09:30', '13:00', '16:30'];
        foreach ([0, 1] as $dayOffset) {
            $baseDate = Carbon::today()->addDays($dayOffset);
            foreach ($departureTimes1 as $time) {
                $departure = Carbon::parse("{$baseDate->format('Y-m-d')} {$time}");
                Trip::create([
                    'route_id'        => $route1->id,
                    'departure_time'  => $departure,
                    'arrival_time'    => $departure->copy()->addMinutes($route1->duration_minutes),
                    'vessel_name'     => collect(['MV Sta. Ana', 'MV Marinduque', 'MV Dalahican'])->random(),
                    'status'          => 'scheduled',
                    'available_seats' => rand(50, 200),
                ]);
            }
        }

        // ── Trips — Route 2 (today) ───────────────────────────────────────────
        $departureTimes2 = ['07:00', '14:00'];
        foreach ($departureTimes2 as $time) {
            $departure = Carbon::parse(Carbon::today()->format('Y-m-d') . ' ' . $time);
            Trip::create([
                'route_id'        => $route2->id,
                'departure_time'  => $departure,
                'arrival_time'    => $departure->copy()->addMinutes($route2->duration_minutes),
                'vessel_name'     => 'MV Batangas Star',
                'status'          => 'scheduled',
                'available_seats' => rand(30, 100),
            ]);
        }
    }
}