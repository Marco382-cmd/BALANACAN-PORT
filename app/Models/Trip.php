<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    // Optional: explicitly define the table if not standard 'trips'
    // protected $table = 'trips';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'route_id',
        'departure_time',
        'status',
        // add other fields here
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'departure_time' => 'datetime', // this converts it automatically to Carbon
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}