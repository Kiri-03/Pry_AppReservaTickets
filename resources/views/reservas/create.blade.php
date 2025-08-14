<x-app-layout>
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Revisión del vuelo</h1>

    <div id="offerBox" class="space-y-4"></div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl">
      <div class="md:col-span-2">
        <div class="p-4 rounded-xl border bg-white">
            <h2 class="font-semibold mb-2">Selección de asientos</h2>
            <div id="seatBox"><p class="text-sm text-gray-600">Cargando asientos…</p></div>
        </div>
        </div>


      <div class="md:col-span-1">
        <!-- Resumen de precio -->
        <div class="p-4 rounded-xl border bg-white" id="summaryBox"></div>
        <button id="confirmBtn"
          class="mt-4 w-full px-5 py-3 bg-orange-600 text-white rounded-lg shadow hover:bg-orange-700">
          Continuar con los datos del viajero
        </button>
      </div>
    </div>
  </div>

  <script>
  // helpers
  const fmtDur = (iso) => {
    if (!iso) return '';
    const h = iso.match(/(\d+)H/); const m = iso.match(/(\d+)M/);
    return `${h? h[1]+'h ':''}${m? m[1]+'m':''}`.trim();
  };
  const money = (a,c='USD') => `${c} ${Number(a).toLocaleString()}`;
  const segKey = (s) => `${s.carrierCode}${s.number}-${s.departure?.iataCode}-${s.arrival?.iataCode}-${s.departure?.at}`;

  // estado global de asientos seleccionados por segmento
  const getSel = () => JSON.parse(sessionStorage.getItem('selectedSeats') || '{}');
  const setSel = (obj) => sessionStorage.setItem('selectedSeats', JSON.stringify(obj));

  // pinta una grilla simple de asientos (demo)
  function renderSeatGrid(container, segment, paxTotal, preselected = []) {
    const cols = ['A','B','C','D','E','F'];
    const rows = Array.from({length: 24}, (_,i)=> i+1);
    const k = segKey(segment);

    const selected = new Set(preselected);
    const occupied = new Set(); // simulado: 10% ocupado
    rows.forEach(r => cols.forEach(c => { if (Math.random() < 0.10) occupied.add(`${r}${c}`)}));

    const header = document.createElement('div');
    header.className = 'mb-2 flex items-center text-xs gap-3';
    header.innerHTML = `
      <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded border bg-white inline-block"></span> Libre</span>
      <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded bg-gray-300 inline-block"></span> Ocupado</span>
      <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded bg-blue-600 inline-block"></span> Seleccionado</span>
      <span class="ml-auto">Debes elegir: <b>${paxTotal}</b> — <span data-left="${k}"></span></span>
    `;
    container.appendChild(header);

    const grid = document.createElement('div');
    grid.className = 'inline-block rounded-lg border p-3 bg-white';
    const tbl = document.createElement('div');
    tbl.className = 'grid grid-cols-[auto_repeat(6,2.2rem)] gap-2 items-center';

    // encabezados columnas
    tbl.appendChild(document.createElement('div'));
    cols.forEach(c => {
      const hd = document.createElement('div');
      hd.className = 'text-center text-xs text-gray-500'; hd.textContent = c;
      tbl.appendChild(hd);
    });

    // util: refrescar contador “faltan”
    const updateLeft = () => {
      const left = Math.max(0, paxTotal - selected.size);
      const tag = container.querySelector(`[data-left="${k}"]`);
      if (tag) tag.textContent = left === 0 ? 'listo ✅' : `faltan ${left}`;
      validateAllSegments(); // habilita/deshabilita el botón global
    };
    updateLeft();

    // filas
    rows.forEach(r => {
      const label = document.createElement('div');
      label.className = 'text-xs text-gray-500 pr-1'; label.textContent = r;
      tbl.appendChild(label);

      cols.forEach(c => {
        const code = `${r}${c}`;
        const isOcc = occupied.has(code);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.dataset.code = code;
        btn.className = `
          h-8 w-8 rounded border text-[11px]
          ${isOcc ? 'bg-gray-300 cursor-not-allowed' : 'bg-white hover:bg-blue-50'}
        `.trim();
        btn.textContent = c;

        // marcar preseleccionados
        if (!isOcc && selected.has(code)) {
          btn.classList.remove('bg-white');
          btn.classList.add('bg-blue-600','text-white');
        }

        btn.addEventListener('click', () => {
          if (isOcc) return;
          if (selected.has(code)) {
            selected.delete(code);
            btn.classList.remove('bg-blue-600','text-white');
            btn.classList.add('bg-white');
          } else {
            if (selected.size >= paxTotal) return; // límite EXACTO
            selected.add(code);
            btn.classList.remove('bg-white');
            btn.classList.add('bg-blue-600','text-white');
          }
          // persistir
          const all = getSel();
          all[k] = Array.from(selected);
          setSel(all);
          // mostrar lista en cabecera de segmento (si existe)
          const tag = container.parentElement.querySelector(`[data-tag="${k}"]`);
          if (tag) tag.textContent = (all[k] && all[k].length) ? all[k].join(', ') : '—';
          updateLeft();
        });

        tbl.appendChild(btn);
      });
    });

    grid.appendChild(tbl);
    container.appendChild(grid);
  }

  // valida que TODOS los segmentos tengan exactamente paxTotal asientos
  function validateAllSegments() {
    const of = window.__OFFER__;
    const pax = window.__PAX_TOTAL__;
    const sel = getSel();
    const btn = document.getElementById('confirmBtn');

    const segs = (of.itineraries || []).flatMap(it => it.segments || []);
    const ok = segs.every(s => (sel[segKey(s)]?.length || 0) === pax);

    btn.disabled = !ok;
    btn.classList.toggle('opacity-50', !ok);
    btn.classList.toggle('cursor-not-allowed', !ok);
    const warn = document.getElementById('btnHelp');
    if (warn) warn.textContent = ok ? '' : `Selecciona ${pax} asiento(s) en cada segmento para continuar.`;
  }

  window.addEventListener('DOMContentLoaded', () => {
    const raw = sessionStorage.getItem('selectedOffer');
    if (!raw) { window.location.href = "{{ url('/') }}"; return; }
    const of = JSON.parse(raw);
    window.__OFFER__ = of;

    const paxObj = JSON.parse(sessionStorage.getItem('passengers') || '{}');
    const pax = (paxObj.adults ?? 1) + (paxObj.children ?? 0) + (paxObj.infants ?? 0) || 1;
    window.__PAX_TOTAL__ = pax;

    const offerBox = document.getElementById('offerBox');
    const seatBox  = document.getElementById('seatBox');

    // ----- pintar itinerarios (ida/vuelta si existen)
    const itineraries = Array.isArray(of.itineraries) ? of.itineraries : [];
    const priceHTML = `<div class="text-2xl font-bold text-blue-600">${money(of.price?.total, of.price?.currency)}</div>`;

    offerBox.innerHTML = itineraries.map((it, i) => {
      const segs = it?.segments || [];
      const salida = segs[0], llegada = segs[segs.length-1];
      const title = i === 0 ? 'Ida' : 'Vuelta';
      return `
        <div class="border rounded-xl p-5 bg-white shadow-sm">
          <div class="flex items-center justify-between">
            <div class="text-lg font-semibold">${title}: ${salida?.departure?.iataCode} → ${llegada?.arrival?.iataCode}</div>
            ${i===0 ? priceHTML : ''}
          </div>
          <div class="mt-3 text-sm text-gray-700">
            Duración total: <b>${fmtDur(it?.duration)}</b> • Segmentos: <b>${segs.length}</b>
          </div>
          <div class="mt-3 border rounded-lg bg-gray-50 p-3">
            ${segs.map((s,idx) => `
              <div class="py-1 text-sm">
                <div class="font-medium flex items-center gap-2">
                  <span class="mt-1 h-2 w-2 rounded-full bg-blue-500 inline-block"></span>
                  Segmento ${idx+1}: ${s.departure.iataCode} → ${s.arrival.iataCode} • ${s.carrierCode}${s.number}
                </div>
                <div class="text-gray-600">${new Date(s.departure.at).toLocaleString()} — ${new Date(s.arrival.at).toLocaleString()} • ${fmtDur(s.duration)}</div>
                <div class="text-xs text-gray-500">Asientos: <span data-tag="${segKey(s)}">—</span></div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    }).join('');

    // ----- seat pickers por segmento, con validación por pasajeros
    const saved = JSON.parse(sessionStorage.getItem('selectedSeats') || '{}');
    seatBox.innerHTML = '';
    itineraries.forEach((it, i) => {
      const block = document.createElement('div');
      block.className = 'mb-6';
      block.innerHTML = `<h3 class="font-medium mb-2">${i===0?'Ida':'Vuelta'}</h3>`;
      (it.segments || []).forEach((s) => {
        const wrap = document.createElement('div');
        wrap.className = 'mb-5';
        seatBox.appendChild(block);
        block.appendChild(wrap);
        renderSeatGrid(wrap, s, pax, saved[segKey(s)] || []);
      });
    });

    // ----- resumen de precio
    const base = of.price?.base ?? of.price?.total;
    const taxes = (Number(of.price?.total) || 0) - (Number(base) || 0);
    document.getElementById('summaryBox').innerHTML = `
      <h3 class="font-semibold mb-2">Detalle de precio</h3>
      <div class="text-sm flex justify-between"><span>Tarifa base</span><span>${money(base, of.price?.currency)}</span></div>
      <div class="text-sm flex justify-between"><span>Impuestos y tasas</span><span>${money(taxes, of.price?.currency)}</span></div>
      <hr class="my-2">
      <div class="font-bold flex justify-between"><span>Total</span><span>${money(of.price?.total, of.price?.currency)}</span></div>
      <div class="mt-2 text-xs text-gray-500">Pasajeros: <b>${pax}</b></div>
      <div id="btnHelp" class="mt-2 text-xs text-red-600"></div>
    `;

    // botón continuar: bloqueado hasta cumplir la regla
    const btn = document.getElementById('confirmBtn');
    validateAllSegments();
    btn.addEventListener('click', () => {
      // si llega aquí es porque validateAllSegments() ya habilitó el botón
      window.location.href = "{{ url('/reservas/pasajero') }}";
    });
  });
</script>


</x-app-layout>
