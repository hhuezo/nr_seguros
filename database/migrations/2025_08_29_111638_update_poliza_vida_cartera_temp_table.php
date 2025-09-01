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
        Schema::table('poliza_vida_cartera_temp', function (Blueprint $table) {
            // Renombrar columna Nit -> CarnetResidencia
            $table->renameColumn('Nit', 'CarnetResidencia');

            // Agregar nuevas columnas
            $table->string('TipoDeuda', 50)->nullable();
            $table->string('PorcentajeExtraprima', 50)->nullable();
            $table->string('TipoDocumento', 50)->nullable();
            $table->decimal('SaldoInteresMora', 16, 4)->nullable();
            $table->string('NombreSociedad', 150)->nullable();

            // Nuevos campos solicitados
            $table->decimal('SaldoCapital', 28, 14)->nullable();
            $table->decimal('Intereses', 28, 14)->nullable();
            $table->decimal('InteresesMoratorios', 28, 14)->nullable();
            $table->decimal('InteresesCovid', 28, 14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_vida_cartera_temp', function (Blueprint $table) {
            // Revertir nombre de columna
            $table->renameColumn('CarnetResidencia', 'Nit');

            // Eliminar las nuevas columnas
            $table->dropColumn([
                'TipoDeuda',
                'PorcentajeExtraprima',
                'TipoDocumento',
                'SaldoInteresMora',
                'NombreSociedad',
                'SaldoCapital',
                'Intereses',
                'InteresesMoratorios',
                'InteresesCovid',
            ]);
        });
    }
};
