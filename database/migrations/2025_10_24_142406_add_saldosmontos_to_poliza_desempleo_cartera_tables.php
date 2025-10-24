<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaldosMontosToPolizaDesempleoCarteraTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 🔹 1. Tabla principal
        Schema::table('poliza_desempleo_cartera', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_desempleo_cartera', 'SaldosMontos')) {
                $table->unsignedBigInteger('SaldosMontos')->nullable()->after('Id');

                $table->foreign('SaldosMontos')
                      ->references('Id')
                      ->on('saldos_montos')
                      ->onDelete('set null');
            }
        });

        // 🔹 2. Tabla temporal
        Schema::table('poliza_desempleo_cartera_temp', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_desempleo_cartera_temp', 'SaldosMontos')) {
                $table->unsignedBigInteger('SaldosMontos')->nullable()->after('Id');

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
        Schema::table('poliza_desempleo_cartera', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_desempleo_cartera', 'SaldosMontos')) {
                $table->dropForeign(['SaldosMontos']);
                $table->dropColumn('SaldosMontos');
            }
        });

        Schema::table('poliza_desempleo_cartera_temp', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_desempleo_cartera_temp', 'SaldosMontos')) {
                $table->dropForeign(['SaldosMontos']);
                $table->dropColumn('SaldosMontos');
            }
        });
    }
}
