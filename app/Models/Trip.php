<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'route_id',
        'departure_time',
        'vessel_name',
        'available_passenger_slots',
        'available_vehicle_slots',
        'status',
        'notes',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}