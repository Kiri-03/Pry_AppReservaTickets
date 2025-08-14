<x-app-layout>
    <header class="relative shadow">
        <div>
            <div class="relative h-32 overflow-hidden shadow-sm">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('/storage/avion.webp') }}');"></div>
                <div class="absolute inset-0 bg-black bg-opacity-40"></div> <!-- capa oscura para contraste -->
                <div class="relative flex items-center justify-center h-full">
                    <h1 class="text-2xl font-semibold text-white">Busca tu vuelo ideal</h1>
                </div>
            </div>
        </div>
    </header>
    <div class="py-5 min-h-screen bg-sky-100">


    <div class="rounded-xl shadow-lg mt-8 max-w-7xl mx-auto px-4 sm:px-6 py-6 w-full">
      <!-- Tipo de viaje -->
          <div class="flex items-center gap-6 text-sm text-gray-700 mb-6" style="border-bottom: 2px solid #000000FF; padding-bottom: 1rem;">
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="tripType" value="round" class="text-blue-600" checked>
              <span>Ida y Vuelta</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="tripType" value="oneway" class="text-blue-600">
              <span>Sólo Ida</span>
            </label>
          </div>

        <form id="flight-search-form" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
          <!-- Origen y destino -->
          <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                  <label for="origin" class="block text-sm font-medium text-gray-700">Desde</label>
                  <select id="origin" name="origin" placeholder="Escribe ciudad, país o IATA"
                  class="mt-2 w-full p-2 border border-gray-300 rounded-md"></select>
              </div>

              <div>
                  <label for="destination" class="block text-sm font-medium text-gray-700">Hacia</label>
                  <select id="destination" name="destination" placeholder="Escribe ciudad, país o IATA"
                  class="mt-2 w-full p-2 border border-gray-300 rounded-md"></select>
              </div>
          </div>


        <!-- Fechas -->
          <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 md:col-span-2">
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">Fecha de salida</label>
              <input type="date" id="date" name="date"
                class="mt-2 w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition">
            </div>
            <div id="returnDateContainer">
              <label for="returnDate" class="block text-sm font-medium text-gray-700">Fecha de regreso</label>
              <input type="date" id="returnDate" name="returnDate"
                class="mt-2 w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition">
            </div>
          </div>

        <!-- Pasajeros -->
        <div class="relative lg:col-span-1 md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Pasajeros</label>

          <button type="button" id="passengerBtn"
            class="mt-2 w-full md:w-auto px-4 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 flex items-center gap-2">
            <span id="passengerLabel">1 Adulto</span>
          </button>

            <!-- Popover -->
            <div id="passengerPopover"
                class="hidden absolute z-10 mt-2 w-80 bg-white rounded-xl shadow-lg border p-4">
              <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-medium">Adulto</div>
                    <div class="text-gray-500 text-xs">(12+)</div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" class="px-2 py-1 border rounded" data-dec="adults">−</button>
                    <span id="adultsCount" class="w-6 text-center">1</span>
                    <button type="button" class="px-2 py-1 border rounded" data-inc="adults">+</button>
                  </div>
                </div>
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-medium">Niño</div>
                    <div class="text-gray-500 text-xs">(2–11)</div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" class="px-2 py-1 border rounded" data-dec="children">−</button>
                    <span id="childrenCount" class="w-6 text-center">0</span>
                    <button type="button" class="px-2 py-1 border rounded" data-inc="children">+</button>
                  </div>
                </div>
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-medium">Infante</div>
                    <div class="text-gray-500 text-xs"><2 años </div> 
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" class="px-2 py-1 border rounded" data-dec="infants">−</button>
                    <span id="infantsCount" class="w-6 text-center">0</span>
                    <button type="button" class="px-2 py-1 border rounded" data-inc="infants">+</button>
                  </div>
                </div>
                <button type="button" id="passengerDone"
                  class="mt-2 w-full px-4 py-2 bg-blue-600 text-white rounded-md">Hecho</button>
              </div>
            </div>
          </div>
        <!-- Botón -->
        <div class="md:col-span-3">
            <button type="submit"
            class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-md shadow-md hover:from-blue-600 hover:to-indigo-700 transition transform hover:scale-[1.02]">
            Buscar vuelos
            </button>
        </div>
        </form>
    </div>
   
    <div id="results" class="mt-8 max-w-4xl mx-auto"></div>
    
    </div>

    
<script>
(function() {
  // Trip type toggle
  const tripRadios = document.querySelectorAll('input[name="tripType"]');
  const returnInput = document.getElementById('returnDate');
  const returnDateContainer = document.getElementById('returnDateContainer');
  const syncReturnState = () => {
    const type = [...tripRadios].find(r => r.checked)?.value;
    const disable = (type === 'oneway');
    returnInput.disabled = disable;
    returnInput.classList.toggle('opacity-50', disable);
    returnDateContainer.classList.toggle('hidden', disable);
    if (disable) returnInput.value = '';
  };
  tripRadios.forEach(r => r.addEventListener('change', syncReturnState));
  syncReturnState();

  // Pasajeros popover
  const pop = document.getElementById('passengerPopover');
  const btn = document.getElementById('passengerBtn');
  const done = document.getElementById('passengerDone');
  const label = document.getElementById('passengerLabel');

  const counts = { adults: 1, children: 0, infants: 0 };

  const renderLabel = () => {
    const total = counts.adults + counts.children + counts.infants;
    const parts = [];
    if (counts.adults) parts.push(`${counts.adults} ${counts.adults===1?'Adulto':'Adultos'}`);
    if (counts.children) parts.push(`${counts.children} ${counts.children===1?'Niño':'Niños'}`);
    if (counts.infants) parts.push(`${counts.infants} ${counts.infants===1?'Infante':'Infantes'}`);
    label.textContent = parts.join(', ') || '1 Adulto';
  };
  const syncSpans = () => {
    document.getElementById('adultsCount').textContent = counts.adults;
    document.getElementById('childrenCount').textContent = counts.children;
    document.getElementById('infantsCount').textContent = counts.infants;
  };

  btn.addEventListener('click', () => { pop.classList.toggle('hidden'); });
  done.addEventListener('click', () => { pop.classList.add('hidden'); });

  pop.addEventListener('click', (e) => {
    const inc = e.target.dataset.inc;
    const dec = e.target.dataset.dec;
    if (inc) {
      if (inc === 'adults') counts.adults++;
      if (inc === 'children') counts.children++;
      if (inc === 'infants') counts.infants++;
      syncSpans(); renderLabel();
    }
    if (dec) {
      if (dec === 'adults') counts.adults = Math.max(1, counts.adults-1);
      if (dec === 'children') counts.children = Math.max(0, counts.children-1);
      if (dec === 'infants') counts.infants = Math.max(0, counts.infants-1);
      syncSpans(); renderLabel();
    }
  });

  // Exponer en form submit
  window.__PASSENGERS__ = counts;
})();
</script>


<script>
window.addEventListener('load', async () => {
  const URL_JSON = 'http://localhost:8000/aeropuertos_comerciales.json';

  try {
    // 1) Traer el JSON UNA SOLA VEZ
    const resp = await fetch(URL_JSON, { cache: 'no-store' });
    if (!resp.ok) throw new Error(`HTTP ${resp.status} al pedir ${URL_JSON}`);
    const raw = await resp.json();

    // 2) Normalizar: tu archivo trae un ARRAY, pero dejamos soporte por si viniera como objeto { locations: [...] }
    const aeropuertos = Array.isArray(raw) ? raw : (raw?.locations ?? []);
    if (!Array.isArray(aeropuertos) || aeropuertos.length === 0) {
      alert('No se encontraron aeropuertos.');
      return;
    }

    // 3) Mapear a opciones para TomSelect
    const options = aeropuertos
      .filter(a => a?.iata_code && String(a.iata_code).trim() !== '')
      .map(a => {
        const iata = String(a.iata_code).trim();
        const name = a?.name ?? '';
        const city = a?.municipality ?? '';
        const country = a?.iso_country ?? '';
        return {
          value: iata,
          name,
          city,
          country,
          iata,
          text: `${name} — ${city}, ${country} (${iata})`
        };
      });

    // 4) Config común TomSelect
    const common = {
      options,
      valueField: 'value',
      labelField: 'text',
      searchField: ['text','name','city','country','iata'],
      maxOptions: 500,
      create: false,
      persist: false,
      placeholder: 'Escribe para buscar…',
      render: {
        option: (item, escape) => `
          <div class="py-1 px-2 flex flex-col gap-1 text-sm text-gray-800 ">
            <div class="font-medium">${escape(item.name)} <span class="text-xs text-gray-500">(${escape(item.iata)})</span></div>
            <div class="text-xs text-gray-500">${escape(item.city)} — ${escape(item.country)}</div>
          </div>
        `,
        item: (item, escape) => `
          <div>${escape(item.city)} — ${escape(item.country)} <span class="text-xs text-gray-500">(${escape(item.iata)})</span></div>
        `
      }
    };

    // 5) Instanciar selects
    const originSelect = new TomSelect('#origin', common);
    const destinationSelect = new TomSelect('#destination', common);

    // 6) Evitar seleccionar el mismo aeropuerto en ambos
    originSelect.on('change', (val) => {
      // restaurar listado filtrado en destino
      destinationSelect.clear();              // limpia valor seleccionado
      destinationSelect.clearOptions();       // borra opciones actuales
      destinationSelect.addOptions(
        options.filter(o => o.value !== val)  // excluye el seleccionado en origen
      );
      destinationSelect.refreshOptions(false);
    });

    console.log(`Aeropuertos cargados: ${options.length}`);
  } catch (err) {
    console.error('Error inicializando selects:', err);
    alert('Error al cargar los aeropuertos. Revisa la consola.');
  }
});
</script>
<script>
(function () {
  const form = document.getElementById('flight-search-form');
  const resultsBox = document.getElementById('results');

  const showMsg = (html) => resultsBox.innerHTML = html;

  const fmtDur = iso => {
    if (!iso) return '';
    const h = iso.match(/(\d+)H/);
    const m = iso.match(/(\d+)M/);
    return `${h ? h[1] + 'h ' : ''}${m ? m[1] + 'm' : ''}`.trim();
  };

  const fmtMoney = (amount, currency) => `${currency} ${Number(amount).toLocaleString()}`;

  // delegación de clic para "Reservar" (una sola vez)
  resultsBox.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-reservar');
    if (!btn) return;
    const idx = parseInt(btn.dataset.idx, 10);
    const offer = window.__OFFERS__?.[idx];
    if (!offer) return;
    sessionStorage.setItem('selectedOffer', JSON.stringify(offer));
        // guarda los pasajeros para la siguiente pantalla
    sessionStorage.setItem('passengers', JSON.stringify(window.__PASSENGERS__ || {adults:1, children:0, infants:0}));

    window.location.href = "{{ url('/reservas/nueva') }}";
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const origen = document.querySelector('#origin')?.value?.trim();
    const destino = document.querySelector('#destination')?.value?.trim();
    const fecha = document.querySelector('#date')?.value;

    if (!origen || !destino || !fecha) {
      showMsg(`<div class="bg-yellow-50 text-yellow-800 p-3 rounded">Completa origen, destino y fecha.</div>`);
      return;
    }
    if (origen === destino) {
      showMsg(`<div class="bg-yellow-50 text-yellow-800 p-3 rounded">El origen y destino no pueden ser iguales.</div>`);
      return;
    }

    const tripType = document.querySelector('input[name="tripType"]:checked')?.value || 'round';
    const returnDate = document.getElementById('returnDate')?.value || '';
    const { adults, children, infants } = window.__PASSENGERS__ || { adults:1, children:0, infants:0 };
    const nonStop = document.getElementById('nonStop')?.checked ? 'true' : 'false';

    // deshabilitar botón mientras carga (declarar ANTES de posibles returns)
    const btn = form.querySelector('button[type="submit"]');
    const prevText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Buscando...';

    // validar round trip
    if (tripType === 'round' && !returnDate) {
      showMsg(`<div class="bg-yellow-50 text-yellow-800 p-3 rounded">Falta la fecha de regreso.</div>`);
      btn.disabled = false; btn.innerText = prevText;
      return;
    }

    // construir URL primero
    const url = new URL(`{{ url('/api/flights/search') }}`);
    url.searchParams.set('origen', origen);
    url.searchParams.set('destino', destino);
    url.searchParams.set('fecha', fecha);

    // ahora sí: añadir parámetros opcionales
    url.searchParams.set('adults', adults);
    if (children) url.searchParams.set('children', children);
    if (infants) url.searchParams.set('infants', infants);
    if (tripType === 'round') url.searchParams.set('returnDate', returnDate);
    if (nonStop === 'true') url.searchParams.set('nonStop', 'true');

    try {
      const resp = await fetch(url.toString(), {
        method: 'GET',
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
      });

      if (resp.status === 401) {
        showMsg(`<div class="bg-red-50 text-red-700 p-3 rounded">No autorizado. Inicia sesión para buscar vuelos.</div>`);
        return;
      }
      if (resp.status === 422) {
        const data = await resp.json();
        const errs = data.errors || {};
        const list = Object.values(errs).flat().map(e => `<li>${e}</li>`).join('');
        showMsg(`<div class="bg-red-50 text-red-700 p-3 rounded"><b>Datos inválidos:</b><ul class="list-disc pl-6">${list}</ul></div>`);
        return;
      }
      if (!resp.ok) {
        showMsg(`<div class="bg-red-50 text-red-700 p-3 rounded">Error al consultar vuelos. (${resp.status})</div>`);
        return;
      }

      const data = await resp.json();
      const offers = Array.isArray(data?.data) ? data.data : [];

      if (offers.length === 0) {
        showMsg(`<div class="bg-blue-50 text-blue-700 p-3 rounded">No se encontraron vuelos para esa fecha.</div>`);
        return;
      }

      const html = offers.slice(0, 10).map((of, idx) => {
  const price = fmtMoney(of.price?.total, of.price?.currency || 'USD');

  const out = of.itineraries?.[0];       // ida
  const ret = of.itineraries?.[1];       // vuelta (puede no venir en oneway)

  const renderItin = (it, titulo, colorBox) => {
    if (!it) return '';
    const segs = it.segments || [];
    const dur  = fmtDur(it.duration);
    const salida = segs[0];
    const llegada = segs[segs.length - 1];
    const salidaStr  = `${salida?.departure?.iataCode} ${new Date(salida?.departure?.at).toLocaleString()}`;
    const llegadaStr = `${llegada?.arrival?.iataCode} ${new Date(llegada?.arrival?.at).toLocaleString()}`;
    const escalas = Math.max(0, segs.length - 1);
    const badge = escalas === 0
      ? `<span class="px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700">Sin escalas</span>`
      : `<span class="px-2 py-1 text-xs rounded-full bg-amber-50 text-amber-700">${escalas} ${escalas===1?'escala':'escalas'}</span>`;

    const legs = segs.map(s => `
      <div class="flex items-start gap-3 py-2">
        <div class="shrink-0 mt-1 h-2 w-2 rounded-full bg-blue-500"></div>
        <div class="text-sm">
          <div class="font-medium">${s.departure.iataCode} → ${s.arrival.iataCode}</div>
          <div class="text-gray-600">${new Date(s.departure.at).toLocaleString()} • ${new Date(s.arrival.at).toLocaleString()}</div>
          <div class="text-gray-500 text-xs">Vuelo ${s.carrierCode}${s.number} • ${fmtDur(s.duration)}</div>
        </div>
      </div>
    `).join('');

    return `
      <div class="rounded-lg p-3 ${colorBox}">
        <div class="flex items-center justify-between gap-3">
          <div class="text-xs text-gray-500">${titulo}</div>
          ${badge}
        </div>
        <div class="mt-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <div class="text-[11px] text-gray-500">Salida</div>
            <div class="font-semibold">${salidaStr}</div>
          </div>
          <div>
            <div class="text-[11px] text-gray-500">Llegada</div>
            <div class="font-semibold">${llegadaStr}</div>
          </div>
          <div>
            <div class="text-[11px] text-gray-500">Duración</div>
            <div class="font-semibold">${dur}</div>
          </div>
        </div>

        <details class="mt-3 group">
          <summary class="cursor-pointer text-sm text-blue-600 hover:underline">Ver segmentos</summary>
          <div class="mt-2 border rounded-lg p-3 bg-gray-50">
            ${legs}
          </div>
        </details>
      </div>
    `;
  };

  // Aerolíneas (combinadas de ida y vuelta si existen)
  const carriersOut = (out?.segments || []).map(s => s.carrierCode);
  const carriersRet = (ret?.segments || []).map(s => s.carrierCode);
  const aerolineas = [...new Set([...carriersOut, ...carriersRet])].join(', ') || 'Aerolínea';

  return `
    <div class="border rounded-xl p-5 mb-5 bg-white shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <span class="text-gray-500 text-sm">${aerolineas}</span>
        </div>
        <div class="text-2xl font-bold text-blue-600">${price}</div>
      </div>

      <div class="mt-4 grid grid-cols-1 gap-4">
        ${renderItin(out, 'Ida', 'bg-blue-50')}
        ${renderItin(ret, 'Vuelta', 'bg-green-50')}
      </div>

      <div class="mt-5 flex justify-end">
        <button 
          class="btn-reservar px-5 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 transition transform hover:scale-105"
          data-idx="${idx}">
          Reservar
        </button>
      </div>
    </div>
  `;
}).join('');


      showMsg(html);
      window.__OFFERS__ = offers;

    } catch (err) {
      console.error(err);
      showMsg(`<div class="bg-red-50 text-red-700 p-3 rounded">Error inesperado al buscar vuelos.</div>`);
    } finally {
      btn.disabled = false;
      btn.innerText = prevText;
    }
  });
})();
</script>



</x-app-layout>
