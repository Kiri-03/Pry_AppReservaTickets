<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('passengers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $t->string('first_name');
            $t->string('last_name');
            $t->string('document')->nullable();
            $t->date('birthdate')->nullable();
            $t->string('ptype', 3)->default('ADT'); // ADT|CHD|INF
            $t->unsignedSmallInteger('index');      // 1..N
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('passengers'); }
};
