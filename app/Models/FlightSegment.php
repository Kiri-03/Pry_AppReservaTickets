<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSegment extends Model
{
    protected $fillable = [
        'flight_offer_id','direction','seq','carrier_code','flight_number',
        'departure_iata','departure_at','arrival_iata','arrival_at','duration_iso','segment_key'
    ];
    protected $casts = ['departure_at'=>'datetime','arrival_at'=>'datetime'];

    public function offer() { return $this->belongsTo(FlightOffer::class,'flight_offer_id'); }
    public function seatSelections() { return $this->hasMany(SeatSelection::class); }
    public function flightOffer()
    {
        return $this->belongsTo(FlightOffer::class);
    }
}
