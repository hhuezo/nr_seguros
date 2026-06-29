<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $columnas = [];

            foreach (['Beneficiario', 'Propietario'] as $columna) {
                if (Schema::hasColumn('poliza_seguro_cesion_beneficios', $columna)) {
                    $columnas[] = $columna;
                }
            }

            if (!empty($columnas)) {
                $table->dropColumn($columnas);
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Beneficiario')) {
                $table->string('Beneficiario', 200)->nullable()->after('CesionarioId');
            }

            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Propietario')) {
                $table->string('Propietario', 200)->nullable()->after('Observaciones');
            }
        });
    }
};
