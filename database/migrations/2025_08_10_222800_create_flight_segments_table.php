<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flight_segments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('flight_offer_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('direction')->default(0); // 0=ida, 1=vuelta
            $t->unsignedSmallInteger('seq');                  // orden
            $t->string('carrier_code', 3);
            $t->string('flight_number', 8);
            $t->string('departure_iata', 3);
            $t->timestamp('departure_at');
            $t->string('arrival_iata', 3);
            $t->timestamp('arrival_at');
            $t->string('duration_iso', 16)->nullable();       // p.ej. PT1H10M
            $t->string('segment_key')->index();               // clave usada en el front
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('flight_segments'); }
};
