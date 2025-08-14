<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightOffer extends Model
{
    protected $fillable = [
        'provider','provider_offer_id','origin','destination','departure_date',
        'return_date','trip_type','price_total','currency','data'
    ];
    protected $casts = [
        'data' => 'array',
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function segments() { return $this->hasMany(FlightSegment::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }
}
