<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingVehicle extends Model
{
    // DB has only created_at, no updated_at
    public $timestamps   = false;
    const CREATED_AT     = 'created_at';

    protected $fillable = [
        'booking_id',
        'vehicle_fare_id',
        'plate_number',
        'vehicle_description',   // was missing before
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function vehicleFare()
    {
        return $this->belongsTo(VehicleFare::class);
    }
}