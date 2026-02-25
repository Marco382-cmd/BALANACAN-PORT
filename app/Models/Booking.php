<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'booking_reference',
        'travel_date',
        'contact_name',
        'contact_email',
        'contact_phone',
        'total_passengers',
        'total_amount',
        'payment_method',
        'payment_status',
        'booking_status',
        'notes',
    ];

    protected $casts = [
        'travel_date'     => 'date',
        'total_amount'    => 'decimal:2',
        'total_passengers'=> 'integer',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // ── Auto-generate booking reference ────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateReference();
            }
        });
    }

    private static function generateReference(): string
    {
        do {
            $ref = 'BK-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('booking_reference', $ref)->exists());

        return $ref;
    }

    // ── Relationships ───────────────────────────────────────────────────────
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function vehicles()
    {
        return $this->hasMany(BookingVehicle::class);
    }

    // ── Accessors ───────────────────────────────────────────────────────────
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'     => 'success',
            'pending'  => 'warning',
            'failed'   => 'danger',
            'refunded' => 'secondary',
            default    => 'secondary',
        };
    }

    public function getBookingStatusBadgeAttribute(): string
    {
        return match ($this->booking_status) {
            'confirmed' => 'success',
            'pending'   => 'warning',
            'cancelled' => 'danger',
            'boarded'   => 'info',
            default     => 'secondary',
        };
    }
}