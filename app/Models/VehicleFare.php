<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFare extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'vehicle_type',
        'label',
        'size_description',
        'fare_min',
        'fare_max',
        'notes',
    ];

    protected $casts = [
        'fare_min' => 'decimal:2',
        'fare_max' => 'decimal:2',
    ];

    // ── Relationships ───────────────────────────────────────────────────────
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // ── Accessors ───────────────────────────────────────────────────────────
    public function getIconAttribute(): string
    {
        return match ($this->vehicle_type) {
            'bicycle'    => '🚲',
            'motorcycle' => '🏍',
            'car'        => '🚗',
            'van'        => '🚐',
            'suv'        => '🚙',
            'truck'      => '🚛',
            'bus'        => '🚌',
            default      => '🚗',
        };
    }

    public function getFareRangeAttribute(): string
    {
        if ($this->fare_min == $this->fare_max || is_null($this->fare_max)) {
            return '₱' . number_format($this->fare_min, 0);
        }

        return '₱' . number_format($this->fare_min, 0) . ' – ₱' . number_format($this->fare_max, 0);
    }
}