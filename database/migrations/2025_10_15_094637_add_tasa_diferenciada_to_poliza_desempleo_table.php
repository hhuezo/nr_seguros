<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_desempleo', function (Blueprint $table) {
            // 🔹 Agregar el nuevo campo
            $table->boolean('TasaDiferenciada')->default(0)->after('Tasa');
        });


    }

    public function down(): void
    {
        Schema::table('poliza_desempleo', function (Blueprint $table) {
            // 🔹 Quitar el nuevo campo
            $table->dropColumn('TasaDiferenciada');

            // 🔹 Restaurar el campo eliminado
            $table->unsignedBigInteger('Saldos')->nullable()->after('PolizaDesempleo');
        });


    }
};
