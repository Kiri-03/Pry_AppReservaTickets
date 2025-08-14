<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('flight_offer_id')->constrained()->cascadeOnDelete();
            $t->string('code')->unique();                 // p.ej. RSV-20250810-AB12CD
            $t->string('status', 20)->default('draft');   // draft|confirmed|cancelled
            $t->unsignedSmallInteger('passengers_count');
            $t->decimal('total_amount', 10, 2);
            $t->string('currency', 3)->default('USD');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reservations'); }
};
