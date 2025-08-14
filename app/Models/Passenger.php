<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = ['reservation_id','first_name','last_name','document','birthdate','ptype','index'];
    protected $casts = ['birthdate'=>'date'];

    public function reservation() { return $this->belongsTo(Reservation::class); }
    public function seats() { return $this->hasMany(SeatSelection::class); }
}

