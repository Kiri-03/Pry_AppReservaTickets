<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id','flight_offer_id','code','status','passengers_count','total_amount','currency'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function offer() { return $this->belongsTo(FlightOffer::class,'flight_offer_id'); }
    public function passengers() { return $this->hasMany(Passenger::class); }
    public function seats() { return $this->hasMany(SeatSelection::class); }
    // Pasajeros de la reserva

    // Selecciones de asiento de la reserva
    public function seatSelections()
    {
        return $this->hasMany(SeatSelection::class);
    }

    // Oferta de vuelo asociada
    public function flightOffer()
    {
        return $this->belongsTo(FlightOffer::class);
    }

    // Segmentos del vuelo de ESTA reserva (filtra por el mismo flight_offer_id)
    public function flightSegments()
    {
        return $this->hasMany(FlightSegment::class, 'flight_offer_id', 'flight_offer_id');
    }

}
