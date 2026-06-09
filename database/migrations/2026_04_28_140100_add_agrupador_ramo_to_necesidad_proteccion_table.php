<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->unsignedBigInteger('AgrupadorRamo')->nullable()->after('Nombre');
            $table->foreign('AgrupadorRamo', 'fk_necesidad_proteccion_agrupador_ramo')
                ->references('Id')
                ->on('agrupador_ramo')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->dropForeign('fk_necesidad_proteccion_agrupador_ramo');
            $table->dropColumn('AgrupadorRamo');
        });
    }
};

