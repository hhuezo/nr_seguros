<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_vida_cartera_temp_historial', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->integer('PolizaVida')->nullable();
            $table->string('CarnetResidencia', 30)->nullable();
            $table->string('Dui', 20)->nullable();
            $table->string('Pasaporte', 50)->nullable();
            $table->string('Nacionalidad', 100)->nullable();
            $table->string('FechaNacimiento', 20)->nullable();
            $table->string('TipoPersona', 20)->nullable();
            $table->char('Sexo', 1)->nullable();
            $table->string('PrimerApellido', 50)->nullable();
            $table->string('SegundoApellido', 100)->nullable();
            $table->string('ApellidoCasada', 200)->nullable();
            $table->string('PrimerNombre', 200)->nullable();
            $table->string('SegundoNombre', 50)->nullable();
            $table->string('FechaOtorgamiento', 20)->nullable();
            $table->string('FechaVencimiento', 20)->nullable();
            $table->string('NumeroReferencia', 255)->nullable();
            $table->decimal('SumaAsegurada', 28, 14)->nullable();
            $table->unsignedBigInteger('User');
            $table->integer('Axo')->nullable();
            $table->integer('Mes')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaFinal')->nullable();
            $table->date('FechaNacimientoDate')->nullable();
            $table->date('FechaOtorgamientoDate')->nullable();
            $table->integer('Edad')->nullable();
            $table->integer('EdadDesembloso')->nullable();
            $table->integer('TipoError')->nullable();
            $table->boolean('Rehabilitado')->default(0);
            $table->boolean('NoValido')->default(0);
            $table->integer('PolizaVidaTipoCartera');
            $table->decimal('Tasa', 10, 7)->nullable();
            $table->decimal('MontoMaximoIndividual', 28, 14)->default(0);
            $table->string('TipoDeuda', 50)->nullable();
            $table->string('PorcentajeExtraprima', 50)->nullable();
            $table->string('TipoDocumento', 50)->nullable();
            $table->decimal('SaldoInteresMora', 16, 4)->nullable();
            $table->string('NombreSociedad', 150)->nullable();
            $table->decimal('SaldoCapital', 28, 14)->nullable();
            $table->decimal('Intereses', 28, 14)->nullable();
            $table->decimal('InteresesMoratorios', 28, 14)->nullable();
            $table->decimal('InteresesCovid', 28, 14)->nullable();

            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_vida_cartera_temp_historial');
    }
};
