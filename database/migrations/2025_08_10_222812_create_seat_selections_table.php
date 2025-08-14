<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seat_selections', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')
                ->nullable()
                ->constrained();
                

            $t->foreignId('flight_segment_id')
                ->nullable()
                ->constrained();
                

            $t->foreignId('passenger_id')
                ->nullable()
                ->constrained();
                 // 🔹 Rompe el ciclo de cascadas en SQL Server

            $t->string('seat_code', 6); // ej. 12A
            $t->timestamps();

            $t->unique(['reservation_id','flight_segment_id','seat_code']);
        });

    }
    public function down(): void { Schema::dropIfExists('seat_selections'); }
};
