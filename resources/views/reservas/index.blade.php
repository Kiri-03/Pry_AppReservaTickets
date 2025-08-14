<x-app-layout>
    <div class="py-8 min-h-screen max-w-7xl mx-auto px-4">
        @if (session('ok'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                {{ session('ok') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Reservas</h1>
        </div>

        <div class="mt-6 overflow-x-auto bg-white/90 backdrop-blur rounded-xl shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-left">Contacto</th>
                        <th class="px-4 py-3 text-left">Pasajeros</th>
                        <th class="px-4 py-3 text-left">Asientos</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Creada</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($reservations as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $r->id }}</td>
                            <td class="px-4 py-3 font-mono">{{ $r->code ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $r->contact_name ?? '—' }}</div>
                                <div class="text-gray-500">{{ $r->contact_email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                {{ $r->passengers->count() }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $r->seatSelections->count() }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $r->status === 'pending',
                                        'bg-green-100 text-green-800' => $r->status === 'confirmed',
                                        'bg-red-100 text-red-800' => $r->status === 'cancelled',
                                    ])">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 justify-end">
                                    
                                    <a href="{{ route('reservations.edit', $r) }}"
                                       class="px-3 py-1 rounded-lg bg-blue-600 text-white">Editar</a>
                                    
                                    
                                    <form method="POST" action="{{ route('reservations.destroy', $r) }}"
                                          onsubmit="return confirm('¿Eliminar esta reserva?');">
                                        @csrf @method('DELETE')
                                        <button class="px-3 py-1 rounded-lg bg-red-600 text-white">Eliminar</button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">Sin resultados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    </div>
</x-app-layout>
