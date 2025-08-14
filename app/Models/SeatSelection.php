<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatSelection extends Model
{
    protected $fillable = ['reservation_id','flight_segment_id','passenger_id','seat_code'];

    public function reservation() { return $this->belongsTo(Reservation::class); }
    public function segment() { return $this->belongsTo(FlightSegment::class,'flight_segment_id'); }
    public function passenger() { return $this->belongsTo(Passenger::class); }
}

