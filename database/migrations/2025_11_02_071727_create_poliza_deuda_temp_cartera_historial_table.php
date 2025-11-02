<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_deuda_temp_cartera_historial', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->integer('PolizaDeudaTipoCartera');
            $table->integer('LineaCredito')->nullable();
            $table->decimal('Tasa', 10, 8)->nullable();
            $table->decimal('TotalCredito', 28, 14)->nullable();
            $table->integer('EdadDesembloso')->nullable();
            $table->string('CarnetResidencia', 30)->nullable();
            $table->string('Dui', 20)->nullable();
            $table->string('Pasaporte', 50)->nullable();
            $table->string('Nacionalidad', 100)->nullable();
            $table->string('FechaNacimiento', 20)->nullable();
            $table->string('TipoPersona', 20)->nullable();
            $table->string('PrimerApellido', 50)->nullable();
            $table->string('SegundoApellido', 100)->nullable();
            $table->string('ApellidoCasada', 200)->nullable();
            $table->string('PrimerNombre', 200)->nullable();
            $table->string('SegundoNombre', 50)->nullable();
            $table->string('NombreSociedad', 200)->nullable();
            $table->char('Sexo', 1)->nullable();
            $table->string('FechaOtorgamiento', 20)->nullable();
            $table->string('FechaVencimiento', 20)->nullable();
            $table->string('NumeroReferencia', 255)->nullable();
            $table->decimal('MontoOtorgado', 28, 14)->nullable();
            $table->decimal('SaldoCapital', 28, 14)->nullable();
            $table->decimal('Intereses', 28, 14)->nullable();
            $table->decimal('MoraCapital', 28, 14)->nullable();
            $table->decimal('InteresesMoratorios', 28, 14)->nullable();
            $table->decimal('SaldoTotal', 28, 14)->nullable();
            $table->unsignedBigInteger('User');
            $table->integer('Axo')->nullable();
            $table->integer('Mes')->nullable();
            $table->integer('PolizaDeuda')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaFinal')->nullable();
            $table->integer('TipoError')->default(0);
            $table->date('FechaNacimientoDate');
            $table->integer('Edad')->nullable();
            $table->decimal('InteresesCovid', 28, 14)->nullable();
            $table->decimal('MontoNominal', 28, 14)->nullable();
            $table->boolean('NoValido')->default(0);
            $table->string('Perfiles', 5000);
            $table->date('FechaOtorgamientoDate')->nullable();
            $table->decimal('SaldoCumulo', 28, 14)->nullable();
            $table->integer('Excluido')->default(0);
            $table->boolean('OmisionPerfil')->default(0);
            $table->boolean('Rehabilitado')->default(0);
            $table->integer('EdadRequisito')->nullable();
            $table->decimal('MontoRequisito', 28, 14)->nullable();
            $table->boolean('MontoMaximoIndividual')->default(0);
            $table->string('TipoDeuda', 50)->nullable();
            $table->string('PorcentajeExtraprima', 50)->nullable();
            $table->string('TipoDocumento', 50)->nullable();
            $table->decimal('SaldoInteresMora', 16, 4)->nullable();
            $table->integer('PagoAutomatico')->default(0);
            $table->mediumText('Errores')->nullable();

            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_deuda_temp_cartera_historial');
    }
};
