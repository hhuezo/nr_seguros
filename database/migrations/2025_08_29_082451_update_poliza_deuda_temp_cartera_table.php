<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('poliza_deuda_temp_cartera', function (Blueprint $table) {
            // Renombrar columna Nit -> CarnetResidencia
            $table->renameColumn('Nit', 'CarnetResidencia');

            // Agregar nuevas columnas
            $table->string('TipoDeuda', 50)->nullable();
            $table->string('PorcentajeExtraprima', 50)->nullable();
            $table->string('TipoDocumento', 50)->nullable();
            $table->decimal('SaldoInteresMora', 16, 4)->nullable();


            // Eliminar columna Ocupacion
            $table->dropColumn('Ocupacion');
        });


         Schema::table('poliza_deuda_cartera', function (Blueprint $table) {
            // Renombrar columna Nit -> CarnetResidencia
            $table->renameColumn('Nit', 'CarnetResidencia');

            // Agregar nuevas columnas
            $table->string('TipoDeuda', 50)->nullable();
            $table->string('PorcentajeExtraprima', 50)->nullable();
            $table->string('TipoDocumento', 50)->nullable();
            $table->decimal('SaldoInteresMora', 16, 4)->nullable();


            // Eliminar columna Ocupacion
            $table->dropColumn('Ocupacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_deuda_temp_cartera', function (Blueprint $table) {
            // Revertir nombre de columna
            $table->renameColumn('CarnetResidencia', 'Nit');

            // Eliminar las nuevas columnas
            $table->dropColumn(['TipoDeuda', 'PorcentajeExtraprima','TipoDocumento','SaldoInteresMora']);


            // Restaurar columna Ocupacion
            $table->string('Ocupacion')->nullable();
        });


         Schema::table('poliza_deuda_cartera', function (Blueprint $table) {
            // Revertir nombre de columna
            $table->renameColumn('CarnetResidencia', 'Nit');

            // Eliminar las nuevas columnas
            $table->dropColumn(['TipoDeuda', 'PorcentajeExtraprima','TipoDocumento','SaldoInteresMora']);


            // Restaurar columna Ocupacion
            $table->string('Ocupacion')->nullable();
        });
    }
};
