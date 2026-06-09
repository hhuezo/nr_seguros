<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->decimal('PorcentajeComisionNoDeclarativa', 8, 4)->nullable()->after('TipoPoliza');
            $table->tinyInteger('ComisionBomberos')->default(0)->after('PorcentajeComisionNoDeclarativa');
        });
    }

    public function down(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->dropColumn(['PorcentajeComisionNoDeclarativa', 'ComisionBomberos']);
        });
    }
};

