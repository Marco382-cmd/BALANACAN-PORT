<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'origin_code',
        'origin_name',
        'origin_location',
        'destination_code',
        'destination_name',
        'destination_location',
        'duration_minutes',
        'trips_per_day',
        'status',
    ];

    // ── Relationships ───────────────────────────────────────────────────────
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function passengerFares()
    {
        return $this->hasMany(PassengerFare::class);
    }

    public function vehicleFares()
    {
        return $this->hasMany(VehicleFare::class);
    }

    // ── Accessors ───────────────────────────────────────────────────────────
    public function getDurationLabelAttribute(): string
    {
        $h = intdiv($this->duration_minutes, 60);
        $m = $this->duration_minutes % 60;

        if ($h > 0 && $m > 0) return "{$h}h {$m}m";
        if ($h > 0)            return "{$h}h";
        return "{$m}m";
    }

    public function getBasePassengerFareAttribute(): float
    {
        return $this->passengerFares
            ->where('fare_type', 'regular')
            ->first()
            ?->amount ?? 0;
    }
}