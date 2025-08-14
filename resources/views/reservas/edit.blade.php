<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Editar reserva #{{ $reservation->id }}</h1>

        <form method="POST" action="{{ route('reservations.update', $reservation) }}"
              class="bg-white/90 backdrop-blur rounded-xl shadow p-6 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm text-gray-700 mb-1">Estado</label>
                <select name="status" class="border rounded-lg px-3 py-2 w-full">
                    @foreach (['pending'=>'Pendiente','confirmed'=>'Confirmada','cancelled'=>'Cancelada'] as $k=>$v)
                        <option value="{{ $k }}" @selected($reservation->status===$k)>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Nombre de contacto</label>
                    <input name="contact_name" value="{{ old('contact_name', $reservation->contact_name) }}"
                           class="border rounded-lg px-3 py-2 w-full" />
                    @error('contact_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Email de contacto</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $reservation->contact_email) }}"
                           class="border rounded-lg px-3 py-2 w-full" />
                    @error('contact_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teléfono de contacto</label>
                    <input name="contact_phone" value="{{ old('contact_phone', $reservation->contact_phone) }}"
                           class="border rounded-lg px-3 py-2 w-full" />
                    @error('contact_phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('reservations.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
