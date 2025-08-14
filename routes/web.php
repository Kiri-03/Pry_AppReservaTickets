<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationController;

Route::view('/', 'dashboard'
    )->middleware(['auth', 'verified'])->name('home');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/dashboard/vuelos', function () {
    return view('vuelos');
})->name('dashboard.vuelos');


Route::middleware(['auth'])->group(function () {
    Route::view('/reservas/nueva', 'reservas.create')->name('reservas.create');
    Route::view('/reservas/pasajero', 'reservas.pasajero')->name('reservas.pasajero');
});



Route::middleware('auth')->group(function () {
    Route::post('/reservas/confirmar', [ReservationController::class, 'store'])
        ->name('reservations.store');

});

Route::get('/reservas', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('/reservas/{reservation}/editar', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('/reservas/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservas/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

