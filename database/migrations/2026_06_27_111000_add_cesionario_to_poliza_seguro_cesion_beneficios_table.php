<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'CesionarioId')) {
                $table->unsignedBigInteger('CesionarioId')->nullable()->after('CodigoSesion');
            }
        });

        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $table->foreign('CesionarioId', 'fk_poliza_seguro_cesion_cesionario')
                ->references('Id')
                ->on('cesionario');
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $table->dropForeign('fk_poliza_seguro_cesion_cesionario');
            if (Schema::hasColumn('poliza_seguro_cesion_beneficios', 'CesionarioId')) {
                $table->dropColumn('CesionarioId');
            }
        });
    }
};
