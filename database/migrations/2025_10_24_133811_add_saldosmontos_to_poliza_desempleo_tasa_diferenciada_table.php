<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaldosMontosToPolizaDesempleoTasaDiferenciadaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('poliza_desempleo_tasa_diferenciada', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_desempleo_tasa_diferenciada', 'SaldosMontos')) {
                $table->unsignedBigInteger('SaldosMontos')->nullable()->after('Id');

                // FK sin onUpdate('cascade')
                $table->foreign('SaldosMontos')
                      ->references('Id')
                      ->on('saldos_montos')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_desempleo_tasa_diferenciada', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_desempleo_tasa_diferenciada', 'SaldosMontos')) {
                $table->dropForeign(['SaldosMontos']);
                $table->dropColumn('SaldosMontos');
            }
        });
    }
}
