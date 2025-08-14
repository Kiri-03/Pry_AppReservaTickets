<x-app-layout>
  <div class="max-w-5xl mx-auto py-10 px-4">
    @if (session('ok'))
      <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-800">{{ session('ok') }}</div>
    @endif

    <h1 class="text-2xl font-bold mb-6">Reserva {{ $reservation->code }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="md:col-span-2 space-y-4">
        <div class="p-4 border rounded-xl bg-white">
          <h2 class="font-semibold mb-2">Itinerarios</h2>
          @foreach ($reservation->offer->segments->groupBy('direction') as $dir => $segs)
            <div class="mb-3">
              <div class="font-medium">{{ $dir === 0 ? 'Ida' : 'Vuelta' }}</div>
              <div class="text-sm text-gray-700">
                @foreach ($segs as $s)
                  <div> {{ $s->departure_iata }} → {{ $s->arrival_iata }} • {{ $s->carrier_code }}{{ $s->flight_number }} ({{ $s->departure_at }})</div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>

        <div class="p-4 border rounded-xl bg-white">
          <h2 class="font-semibold mb-2">Pasajeros</h2>
          <div class="text-sm text-gray-700">
            @foreach ($reservation->passengers as $p)
              <div>#{{ $p->index }} {{ $p->first_name }} {{ $p->last_name }} ({{ $p->ptype }})</div>
            @endforeach
          </div>
        </div>

        <div class="p-4 border rounded-xl bg-white">
          <h2 class="font-semibold mb-2">Asientos</h2>
          <div class="text-sm text-gray-700">
            @foreach ($reservation->seats as $st)
              <div>
                {{ $st->segment->departure_iata }}→{{ $st->segment->arrival_iata }}
                • PAX #{{ $st->passenger->index }} ({{ $st->passenger->first_name }}) → <b>{{ $st->seat_code }}</b>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="md:col-span-1">
        <div class="p-4 border rounded-xl bg-white">
          <div class="text-sm text-gray-600">Estado</div>
          <div class="text-lg font-semibold mb-2">{{ strtoupper($reservation->status) }}</div>
          <div class="text-sm flex justify-between"><span>Pasajeros</span><span>{{ $reservation->passengers_count }}</span></div>
          <div class="text-sm flex justify-between"><span>Total</span><span>{{ $reservation->currency }} {{ number_format($reservation->total_amount, 2) }}</span></div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
