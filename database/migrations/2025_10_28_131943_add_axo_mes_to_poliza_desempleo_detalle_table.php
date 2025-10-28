<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('poliza_desempleo_detalle', function (Blueprint $table) {
            $table->integer('Axo')->nullable()->after('Desempleo'); // Año, ej. 2025
            $table->unsignedTinyInteger('Mes')->nullable()->after('Axo'); // Mes numérico 1–12
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_desempleo_detalle', function (Blueprint $table) {
            $table->dropColumn(['Axo', 'Mes']);
        });
    }
};
