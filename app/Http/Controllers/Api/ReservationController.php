<?php
namespace App\Http\Controllers\Api;

use App\Models\{FlightOffer, FlightSegment, Reservation, Passenger, SeatSelection};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $req)
    {
        // ID de depuración por petición
        $dbg = 'RSV-'.Str::upper(Str::random(6));
        Log::info("[$dbg] Iniciando store()", [
        'content_type' => $req->header('Content-Type'),
        'method' => $req->method(),
        'keys' => array_keys($req->all()),
    ]);

        // 1) Validación flexible (acepta string JSON o array)
        $data = $req->validate([
            'offer'                => ['required'],                 // string JSON o array
            'seats'                => ['required'],                 // string JSON o array
            'pasajeros'            => ['required','array','min:1'],
            'pasajeros.*.nombre'   => ['required','string','max:80'],
            'pasajeros.*.apellido' => ['required','string','max:80'],
            'pasajeros.*.documento'=> ['nullable','string','max:30'],
            'pasajeros.*.fecha_nacimiento' => ['nullable','date'],
            'pasajeros.*.ptype'    => ['nullable','in:ADT,CHD,INF'],
        ]);
        Log::info("[$dbg] Validación ok", ['pasajeros_count' => count($data['pasajeros'])]);

        // 2) Normalización de entrada
        $offerArr = is_string($data['offer']) ? json_decode($data['offer'], true) : $data['offer'];
        $seatMap  = is_string($data['seats']) ? json_decode($data['seats'], true) : $data['seats'];

        if (!$offerArr || !is_array($offerArr)) {
            Log::warning("[$dbg] offer inválido", ['offer_sample' => substr((string)($data['offer'] ?? ''), 0, 200)]);
            return back()->withErrors(['offer' => 'Formato de oferta inválido'])->withInput();
        }
        if (!is_array($seatMap)) {
            Log::warning("[$dbg] seats inválido", ['seats' => $data['seats']]);
            return back()->withErrors(['seats' => 'Formato de asientos inválido'])->withInput();
        }

        // 3) Derivar datos clave
        try {
            $tripType = isset($offerArr['itineraries'][1]) ? 'round' : 'oneway';
            $origin = $offerArr['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? '';
            $destination = collect($offerArr['itineraries'][0]['segments'])->last()['arrival']['iataCode'] ?? '';
            $depDate = substr($offerArr['itineraries'][0]['segments'][0]['departure']['at'] ?? '', 0, 10);
            $retDate = isset($offerArr['itineraries'][1]) ? substr($offerArr['itineraries'][1]['segments'][0]['departure']['at'], 0, 10) : null;
            $total = (float)($offerArr['price']['total'] ?? 0);
            $currency = $offerArr['price']['currency'] ?? 'USD';
        } catch (Throwable $e) {
            Log::error("[$dbg] Error derivando claves", ['ex' => $e->getMessage()]);
            return back()->withErrors(['offer' => 'Estructura de oferta inesperada'])->withInput();
        }

        Log::info("[$dbg] Datos clave", compact('tripType','origin','destination','depDate','retDate','total','currency'));

        // 4) Transacción con logging detallado
        try {
            DB::beginTransaction();

            // 4.1) Oferta
            $offer = FlightOffer::create([
                'provider' => 'amadeus',
                'provider_offer_id' => $offerArr['id'] ?? null,
                'origin' => $origin,
                'destination' => $destination,
                'departure_date' => $depDate,
                'return_date' => $retDate,
                'trip_type' => $tripType,
                'price_total' => $total,
                'currency' => $currency,
                'data' => $offerArr,
            ]);
            Log::info("[$dbg] Offer creada", ['offer_id' => $offer->id]);

            // 4.2) Segmentos
            $segIdByKey = [];
            foreach ($offerArr['itineraries'] as $i => $it) {
                foreach ($it['segments'] as $j => $s) {
                    $key = $this->segmentKey($s);
                    $seg = FlightSegment::create([
                        'flight_offer_id' => $offer->id,
                        'direction' => $i,
                        'seq' => $j + 1,
                        'carrier_code' => $s['carrierCode'] ?? null,
                        'flight_number' => $s['number'] ?? null,
                        'departure_iata' => $s['departure']['iataCode'] ?? null,
                        'departure_at' => $s['departure']['at'] ?? null,
                        'arrival_iata' => $s['arrival']['iataCode'] ?? null,
                        'arrival_at' => $s['arrival']['at'] ?? null,
                        'duration_iso' => $s['duration'] ?? null,
                        'segment_key' => $key,
                    ]);
                    $segIdByKey[$key] = $seg->id;
                }
            }
            Log::info("[$dbg] Segmentos creados", ['count' => count($segIdByKey)]);

            // 4.3) Reserva
            $reservation = Reservation::create([
                'user_id' => $req->user()?->id,
                'flight_offer_id' => $offer->id,
                'code' => 'RSV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'status' => 'draft',
                'passengers_count' => count($data['pasajeros']),
                'total_amount' => $total,
                'currency' => $currency,
            ]);
            Log::info("[$dbg] Reserva creada", ['reservation_id' => $reservation->id, 'code' => $reservation->code]);

            // 4.4) Pasajeros
            $passengers = [];
            foreach ($data['pasajeros'] as $idx => $p) {
                $passengers[$idx+1] = Passenger::create([
                    'reservation_id' => $reservation->id,
                    'first_name' => $p['nombre'],
                    'last_name' => $p['apellido'],
                    'document' => $p['documento'] ?? null,
                    'birthdate' => $p['fecha_nacimiento'] ?? null,
                    'ptype' => $p['ptype'] ?? 'ADT',
                    'index' => $idx + 1,
                ]);
            }
            Log::info("[$dbg] Pasajeros creados", ['count' => count($passengers)]);

            // 4.5) Asientos
            $asientosCreados = 0;
            foreach ($seatMap as $segKey => $seats) {
                $segId = $segIdByKey[$segKey] ?? null;
                if (!$segId) {
                    Log::warning("[$dbg] segKey no encontrado", ['segKey' => $segKey]);
                    continue;
                }
                foreach (array_values($seats) as $k => $seatCode) {
                    $passenger = $passengers[$k+1] ?? null;
                    if (!$passenger) break;
                    SeatSelection::create([
                        'reservation_id' => $reservation->id,
                        'flight_segment_id' => $segId,
                        'passenger_id' => $passenger->id,
                        'seat_code' => $seatCode,
                    ]);
                    $asientosCreados++;
                }
            }
            Log::info("[$dbg] Asientos creados", ['count' => $asientosCreados]);

            DB::commit();
            Log::info("[$dbg] Transacción commit OK");

            return redirect()->route('reservations.index', $reservation)
                ->with('ok', 'Reserva creada.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("[$dbg] Transacción falló: ".$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Muestra mensaje amable y conserva inputs
            return back()->withErrors(['general' => 'No se pudo guardar la reserva. Revisa el log.'])->withInput();
        }
    }


    /** Genera clave única por segmento del proveedor */
    private function segmentKey(array $s): string
    {
        $dep = ($s['departure']['iataCode'] ?? '').($s['departure']['at'] ?? '');
        $arr = ($s['arrival']['iataCode'] ?? '').($s['arrival']['at'] ?? '');
        $car = ($s['carrierCode'] ?? '').($s['number'] ?? '');
        return md5($dep.'|'.$arr.'|'.$car);
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('viewAny', \App\Models\Reservation::class);

        $reservation->load([
            'offer',
            'offer.segments' => fn($q) => $q->orderBy('direction')->orderBy('seq'),
            'passengers' => fn($q) => $q->orderBy('index'),
            'seats.passenger',
            'seats.segment',
        ]);

        return view('reservas.show', compact('reservation'));
    }

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Reservation::class);

        $reservations = \App\Models\Reservation::query()
            ->where('user_id', auth()->id())        // <<— solo mías
            ->latest()
            ->paginate(10);

        return view('reservas.index', compact('reservations'));
    }

    // GET /reservas/{reservation}/editar
    public function edit(\App\Models\Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        return view('reservas.edit', compact('reservation'));
    }

    // PUT /reservas/{reservation}
    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        $data = $request->validate([
            'status'         => 'required|in:pending,confirmed,cancelled',
            'contact_name'   => 'nullable|string|max:120',
            'contact_email'  => 'nullable|email|max:190',
            'contact_phone'  => 'nullable|string|max:40',
            // agrega aquí más campos editables si aplica
        ]);

        $reservation->update($data);

        return redirect()
            ->route('reservations.index')
            ->with('ok', 'Reserva actualizada correctamente.');
    }

    // DELETE /reservas/{reservation}
   public function destroy(\App\Models\Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('ok','Eliminada');
    }
}
