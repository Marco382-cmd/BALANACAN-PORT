<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerFare extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'fare_type',
        'label',
        'amount',
        'discount_pct',
        'required_id',
        'notes',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'discount_pct' => 'decimal:2',
    ];

    // ── Relationships ───────────────────────────────────────────────────────
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // ── Accessors ───────────────────────────────────────────────────────────
    public function getIconAttribute(): string
    {
        return match ($this->fare_type) {
            'regular' => '🧍',
            'student' => '🎓',
            'senior'  => '👴',
            'pwd'     => '♿',
            'children'=> '👦',
            default   => '🎫',
        };
    }

    public function getLabelAttribute(): string
    {
        // Use DB label if set, otherwise derive from fare_type
        if (!empty($this->attributes['label'])) {
            return $this->attributes['label'];
        }

        return match ($this->fare_type) {
            'regular'  => 'Regular Adult',
            'student'  => 'Student',
            'senior'   => 'Senior Citizen',
            'pwd'      => 'PWD',
            'children' => 'Children',
            default    => ucfirst($this->fare_type),
        };
    }

    public function getRequiredIdAttribute(): string
    {
        if (!empty($this->attributes['required_id'])) {
            return $this->attributes['required_id'];
        }

        return match ($this->fare_type) {
            'student'  => 'School ID required',
            'senior'   => 'Senior Citizen ID required',
            'pwd'      => 'PWD ID required',
            'children' => 'No ID required',
            default    => 'No ID required',
        };
    }
}