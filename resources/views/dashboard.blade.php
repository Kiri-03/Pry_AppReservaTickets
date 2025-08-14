<x-app-layout>

    <div class="py-12 bg-sky-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Bienvenido, {{ Auth::user()->name }} 👋</h1>
                <p class="text-gray-600 mb-6">
                    Esta es tu plataforma para reservar vuelos de forma rápida y segura. Explora destinos, gestiona tus reservas y disfruta de una experiencia optimizada gracias a nuestra arquitectura distribuida.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg shadow hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">Buscar Vuelos</h2>
                        <p class="text-sm text-gray-700 mb-4">Encuentra vuelos disponibles según origen, destino y fecha.</p>
                        <a href="{{ route('dashboard.vuelos') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ir a búsqueda</a>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg shadow hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-green-700 mb-2">Mis Reservas</h2>
                        <p class="text-sm text-gray-700 mb-4">Consulta, modifica o cancela tus reservas activas.</p>
                        <a href="{{ route('reservations.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ver reservas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>