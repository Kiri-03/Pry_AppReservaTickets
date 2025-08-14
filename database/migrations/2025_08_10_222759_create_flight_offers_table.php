<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flight_offers', function (Blueprint $t) {
            $t->id();
            $t->string('provider')->default('amadeus');
            $t->string('provider_offer_id')->nullable();
            $t->string('origin', 3);
            $t->string('destination', 3);
            $t->date('departure_date');
            $t->date('return_date')->nullable();
            $t->string('trip_type', 10); // round | oneway
            $t->decimal('price_total', 10, 2);
            $t->string('currency', 3)->default('USD');
            $t->json('data'); // si tu motor no soporta json: usar longText
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('flight_offers'); }
};
