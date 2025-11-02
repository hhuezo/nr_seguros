<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_desempleo_cartera_temp_historial', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('SaldosMontos')->nullable();
            $table->unsignedBigInteger('PolizaDesempleo');
            $table->string('Dui', 20)->nullable();
            $table->string('Pasaporte', 50)->nullable();
            $table->string('CarnetResidencia', 30)->nullable();
            $table->string('Nacionalidad', 100)->nullable();
            $table->string('FechaNacimiento', 20)->nullable();
            $table->string('TipoPersona', 20)->nullable();
            $table->char('Sexo', 1)->nullable();
            $table->string('PrimerApellido', 50)->nullable();
            $table->string('SegundoApellido', 100)->nullable();
            $table->string('ApellidoCasada', 200)->nullable();
            $table->string('PrimerNombre', 200)->nullable();
            $table->string('SegundoNombre', 50)->nullable();
            $table->string('NombreSociedad', 200)->nullable();
            $table->string('FechaOtorgamiento', 20)->nullable();
            $table->string('FechaVencimiento', 20)->nullable();
            $table->string('NumeroReferencia', 255)->nullable();
            $table->decimal('MontoOtorgado', 28, 14)->nullable();
            $table->decimal('SaldoCapital', 28, 14)->nullable();
            $table->decimal('Intereses', 28, 14)->nullable();
            $table->decimal('MoraCapital', 28, 14)->nullable();
            $table->decimal('InteresesMoratorios', 28, 14)->nullable();
            $table->decimal('InteresesCovid', 28, 14)->nullable();
            $table->decimal('Tarifa', 28, 14)->nullable();
            $table->string('TipoDeuda', 40)->nullable();
            $table->decimal('PorcentajeExtraprima', 28, 14)->nullable();
            $table->decimal('SaldoTotal', 28, 14)->default(0.00000000000000);
            $table->unsignedBigInteger('User');
            $table->integer('Axo')->nullable();
            $table->integer('Mes')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaFinal')->nullable();
            $table->integer('TipoError')->default(0);
            $table->date('FechaNacimientoDate')->nullable();
            $table->date('FechaOtorgamientoDate')->nullable();
            $table->integer('Edad')->nullable();
            $table->integer('EdadDesembloso')->nullable();
            $table->integer('NoValido')->default(0);
            $table->integer('Excluido')->default(0);
            $table->integer('Rehabilitado')->default(0);
            $table->integer('EdadRequisito')->nullable();
            $table->integer('DesempleoTipoCartera')->nullable();
            $table->decimal('TotalCredito', 28, 14)->nullable();
            $table->decimal('Tasa', 28, 14)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_desempleo_cartera_temp_historial');
    }
};
