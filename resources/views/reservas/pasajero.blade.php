<x-app-layout>
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Datos de los pasajeros</h1>

    <!-- Panel de depuración -->
    <div id="debugBox" class="hidden mb-6 rounded-lg border border-yellow-300 bg-yellow-50 text-yellow-900 px-4 py-3 text-sm"></div>

    <!-- Asientos seleccionados -->
    <div id="asientosBox" class="mb-8 p-4 border rounded-xl bg-white shadow-sm">
      <h2 class="font-semibold mb-2">Asientos seleccionados</h2>
      <div id="asientosLista" class="text-sm text-gray-700">Cargando...</div>
    </div>

    <!-- Formulario -->
    <form id="formPasajeros" action="{{ url('/reservas/confirmar') }}" method="POST" class="space-y-6">
      @csrf

      <!-- HIDDEN inputs requeridos por el backend -->
      <input type="hidden" name="offer" id="offerInput">
      <input type="hidden" name="seats" id="seatsInput">

      <div id="pasajerosFields" class="space-y-6">
        <!-- Campos generados con JS -->
      </div>

      <div class="flex justify-end gap-3">
        <button type="button" id="btnTestPayload"
          class="px-4 py-2 rounded-lg border">Probar payload</button>

        <button type="submit" id="btnSubmit"
          class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
          Confirmar reserva
        </button>
      </div>
    </form>
  </div>

  <script>
    // --- md5 mínimo (para alinear claves de segmento con el backend) ---
    // Fuente: implementación compacta de MD5 en JS (licencias permisivas; uso educativo)
    // Si ya tienes una librería, usa esa y elimina esta función.
    function md5(str){function L(k,d){return(k<<d)|(k>>>(32-d))}function K(G,k){var I,J,H,F,E;H=(G&2147483648);F=(k&2147483648);I=(G&1073741824);J=(k&1073741824);E=(G&1073741823)+(k&1073741823);if(I&J){return(E^2147483648^H^F)}if(I|J){if(E&1073741824){return(E^3221225472^H^F)}else{return(E^1073741824^H^F)}}else{return(E^H^F)}}function r(d,F,k){return(d&F)|((~d)&k)}function q(d,F,k){return(d&k)|(F&(~k))}function p(d,F,k){return(d^F^k)}function n(d,F,k){return(F^(d|(~k)))}function u(G,F,aa,Z,k,H,I){G=K(G,K(K(r(F,aa,Z),k),I));return K(L(G,H),F)}function f(G,F,aa,Z,k,H,I){G=K(G,K(K(q(F,aa,Z),k),I));return K(L(G,H),F)}function D(G,F,aa,Z,k,H,I){G=K(G,K(K(p(F,aa,Z),k),I));return K(L(G,H),F)}function t(G,F,aa,Z,k,H,I){G=K(G,K(K(n(F,aa,Z),k),I));return K(L(G,H),F)}function e(G){var Z;var F=G.length;var x=F+8;var k=(x-(x%64))/64;var I=(k+1)*16;var aa=Array(I-1);var d=0;var H=0;while(H<F){Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=(aa[Z]|(G.charCodeAt(H)<<d));H++}Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=(aa[Z]|(128<<d));aa[I-2]=(F<<3);aa[I-1]=(F>>>29);return aa}function B(x){var k="",F="",G,d;for(d=0;d<=3;d++){G=(x>>>(d*8))&255;F="0"+G.toString(16);k=k+F.substr(F.length-2,2)}return k}function J(k){k=k.replace(/\r\n/g,"\n");var d="";for(var F=0;F<k.length;F++){var x=k.charCodeAt(F);if(x<128){d+=String.fromCharCode(x)}else{if((x>127)&&(x<2048)){d+=String.fromCharCode((x>>6)|192);d+=String.fromCharCode((x&63)|128)}else{d+=String.fromCharCode((x>>12)|224);d+=String.fromCharCode(((x>>6)&63)|128);d+=String.fromCharCode((x&63)|128)}}}return d}
      var C=Array(7,12,17,22);var v=Array(5,9,14,20);var w=Array(4,11,16,23);var y=Array(6,10,15,21);
      return (function(k){
        var G=0x67452301;var F=0xEFCDAB89;var aa=0x98BADCFE;var Z=0x10325476;
        var d,eI,eH,eG;var H;eI=e(k);for(H=0;H<eI.length;H+=16){d=G;eH=F;eG=aa;e=Z;
          G=u(G,F,aa,Z,eI[H+0],C[0],0xD76AA478);Z=u(Z,G,F,aa,eI[H+1],C[1],0xE8C7B756);
          aa=u(aa,Z,G,F,eI[H+2],C[2],0x242070DB);F=u(F,aa,Z,G,eI[H+3],C[3],0xC1BDCEEE);
          G=u(G,F,aa,Z,eI[H+4],C[0],0xF57C0FAF);Z=u(Z,G,F,aa,eI[H+5],C[1],0x4787C62A);
          aa=u(aa,Z,G,F,eI[H+6],C[2],0xA8304613);F=u(F,aa,Z,G,eI[H+7],C[3],0xFD469501);
          G=u(G,F,aa,Z,eI[H+8],C[0],0x698098D8);Z=u(Z,G,F,aa,eI[H+9],C[1],0x8B44F7AF);
          aa=u(aa,Z,G,F,eI[H+10],C[2],0xFFFF5BB1);F=u(F,aa,Z,G,eI[H+11],C[3],0x895CD7BE);
          G=u(G,F,aa,Z,eI[H+12],C[0],0x6B901122);Z=u(Z,G,F,aa,eI[H+13],C[1],0xFD987193);
          aa=u(aa,Z,G,F,eI[H+14],C[2],0xA679438E);F=u(F,aa,Z,G,eI[H+15],C[3],0x49B40821);
          G=f(G,F,aa,Z,eI[H+1],v[0],0xF61E2562);Z=f(Z,G,F,aa,eI[H+6],v[1],0xC040B340);
          aa=f(aa,Z,G,F,eI[H+11],v[2],0x265E5A51);F=f(F,aa,Z,G,eI[H+0],v[3],0xE9B6C7AA);
          G=f(G,F,aa,Z,eI[H+5],v[0],0xD62F105D);Z=f(Z,G,F,aa,eI[H+10],v[1],0x02441453);
          aa=f(aa,Z,G,F,eI[H+15],v[2],0xD8A1E681);F=f(F,aa,Z,G,eI[H+4],v[3],0xE7D3FBC8);
          G=f(G,F,aa,Z,eI[H+9],v[0],0x21E1CDE6);Z=f(Z,G,F,aa,eI[H+14],v[1],0xC33707D6);
          aa=f(aa,Z,G,F,eI[H+3],v[2],0xF4D50D87);F=f(F,aa,Z,G,eI[H+8],v[3],0x455A14ED);
          G=D(G,F,aa,Z,eI[H+2],w[0],0xA9E3E905);Z=D(Z,G,F,aa,eI[H+7],w[1],0xFCEFA3F8);
          aa=D(aa,Z,G,F,eI[H+12],w[2],0x676F02D9);F=D(F,aa,Z,G,eI[H+5],w[3],0x8D2A4C8A);
          G=D(G,F,aa,Z,eI[H+0],w[0],0xFFFA3942);Z=D(Z,G,F,aa,eI[H+7],w[1],0x8771F681);
          aa=D(aa,Z,G,F,eI[H+14],w[2],0x6D9D6122);F=D(F,aa,Z,G,eI[H+5],w[3],0xFDE5380C);
          G=t(G,F,aa,Z,eI[H+4],y[0],0xF4292244);Z=t(Z,G,F,aa,eI[H+11],y[1],0x432AFF97);
          aa=t(aa,Z,G,F,eI[H+2],y[2],0xAB9423A7);F=t(F,aa,Z,G,eI[H+9],y[3],0xFC93A039);
          G=t(G,F,aa,Z,eI[H+14],y[0],0x655B59C3);Z=t(Z,G,F,aa,eI[H+1],y[1],0x8F0CCC92);
          aa=t(aa,Z,G,F,eI[H+4],y[2],0xFFEFF47D);F=t(F,aa,Z,G,eI[H+13],y[3],0x85845DD1);
          G=K(G,d);F=K(F,eH);aa=K(aa,eG);Z=K(Z,e)}return (B(G)+B(F)+B(aa)+B(Z)).toLowerCase()})(J(str));}

    function segKeyMD5(s) {
      const dep = (s.departure?.iataCode || '') + (s.departure?.at || '');
      const arr = (s.arrival?.iataCode || '') + (s.arrival?.at || '');
      const car = (s.carrierCode || '') + (s.number || '');
      return md5(dep + '|' + arr + '|' + car);
    }

    window.addEventListener('DOMContentLoaded', () => {
      const paxObj   = JSON.parse(sessionStorage.getItem('passengers') || '{}');
      const totalPax = (paxObj.adults ?? 1) + (paxObj.children ?? 0) + (paxObj.infants ?? 0) || 1;
      const offer    = JSON.parse(sessionStorage.getItem('selectedOffer') || '{}');
      const seatsRaw = JSON.parse(sessionStorage.getItem('selectedSeats') || '{}'); // puede estar con otra key

      // --- pintar asientos por segmento ---
      const asientosBox = document.getElementById('asientosLista');
      const itineraries = Array.isArray(offer.itineraries) ? offer.itineraries : [];
      const segInfo = itineraries.flatMap(it => it.segments || []);

      if (!segInfo.length) {
        asientosBox.textContent = 'No se encontraron segmentos.';
      } else {
        // Normalizamos seats a clave MD5 esperada por backend
        const seatsMD5 = {};
        segInfo.forEach(s => {
          // Si venías usando una clave custom, intenta leerla y migrarla:
          const customKey = `${s.carrierCode}${s.number}-${s.departure?.iataCode}-${s.arrival?.iataCode}-${s.departure?.at}`;
          const md5Key = segKeyMD5(s);
          const list = Array.isArray(seatsRaw[md5Key]) ? seatsRaw[md5Key]
                    : Array.isArray(seatsRaw[customKey]) ? seatsRaw[customKey]
                    : [];
          seatsMD5[md5Key] = list;

          // Render
          const seatList = list.length ? list.join(', ') : '—';
          asientosBox.insertAdjacentHTML('beforeend', `
            <div class="py-1 border-b last:border-0">
              <span class="font-medium">${s.departure.iataCode} → ${s.arrival.iataCode}</span> 
              (${s.carrierCode}${s.number}) - Asientos: <b>${seatList}</b>
            </div>
          `);
        });

        // Guarda seats normalizados en un data-* para usar en submit
        document.getElementById('seatsInput').dataset.value = JSON.stringify(seatsMD5);
      }

      // --- generar campos por pasajero ---
      const cont = document.getElementById('pasajerosFields');
      for (let i = 1; i <= totalPax; i++) {
        cont.insertAdjacentHTML('beforeend', `
          <div class="p-4 border rounded-xl bg-white shadow-sm">
            <h3 class="font-semibold mb-3">Pasajero ${i}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="pasajeros[${i}][nombre]" required class="mt-1 w-full p-2 border rounded-md">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="pasajeros[${i}][apellido]" required class="mt-1 w-full p-2 border rounded-md">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Documento</label>
                <input type="text" name="pasajeros[${i}][documento]" class="mt-1 w-full p-2 border rounded-md">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                <input type="date" name="pasajeros[${i}][fecha_nacimiento]" class="mt-1 w-full p-2 border rounded-md">
              </div>
            </div>
          </div>
        `);
      }

      // --- hook de submit con depuración ---
      const form = document.getElementById('formPasajeros');
      const btnSubmit = document.getElementById('btnSubmit');
      const debugBox = document.getElementById('debugBox');
      const offerInput = document.getElementById('offerInput');
      const seatsInput = document.getElementById('seatsInput');

      function showDebug(msg, obj) {
        debugBox.classList.remove('hidden');
        debugBox.innerHTML = `<b>Debug:</b> ${msg}<pre class="mt-2 whitespace-pre-wrap text-xs">${obj ? JSON.stringify(obj, null, 2) : ''}</pre>`;
        console.warn('DEBUG:', msg, obj || '');
      }

      // Botón para probar payload sin enviar
      document.getElementById('btnTestPayload').addEventListener('click', (e) => {
        const fd = new FormData(form);
        // set offer & seats como strings JSON
        fd.set('offer', JSON.stringify(offer || {}));
        const seatsStr = seatsInput.dataset.value || JSON.stringify({});
        fd.set('seats', seatsStr);

        // Construye un preview amigable
        const preview = { offerExists: !!offer?.id || !!offer?.itineraries, seats: JSON.parse(seatsStr || '{}'), pasajeros: [] };
        // Solo para visualizar pasajeros
        for (let [k, v] of fd.entries()) {
          if (k.startsWith('pasajeros[')) {
            preview.pasajeros.push({ [k]: v });
          }
        }
        showDebug('Payload de prueba (no enviado):', preview);
      });

      form.addEventListener('submit', (e) => {
        // Rellena hidden inputs en cada submit
        offerInput.value = JSON.stringify(offer || {});
        seatsInput.value = seatsInput.dataset.value || JSON.stringify({});

        // Validaciones mínimas
        if (!offer || !Array.isArray(offer.itineraries) || !offer.itineraries.length) {
          e.preventDefault();
          showDebug('Falta la oferta seleccionada (sessionStorage.selectedOffer vacío). Guarda la oferta antes de continuar.');
          return;
        }
        if (!seatsInput.value) {
          e.preventDefault();
          showDebug('Faltan asientos seleccionados o no se pudo normalizar las claves.');
          return;
        }

        // Deshabilita submit y muestra log
        btnSubmit.disabled = true;
        console.log('Enviando formulario con:', {
          offerPreview: { id: offer.id, price: offer?.price, itinCount: offer?.itineraries?.length },
          seatsPreview: JSON.parse(seatsInput.value || '{}')
        });
      });
    });
  </script>
</x-app-layout>
