<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassenger extends Model
{
    public $timestamps = false; // only has created_at

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'booking_id', 'first_name', 'last_name',
        'fare_type', 'id_type', 'id_number', 'amount',
    ];

    protected $casts = ['amount' => 'float'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFareTypeLabelAttribute(): string
    {
        return match ($this->fare_type) {
            'student'  => 'Student',
            'senior'   => 'Senior Citizen',
            'children' => 'Children',
            'pwd'      => 'PWD',
            default    => 'Regular',
        };
    }
}