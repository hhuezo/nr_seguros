<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_residencia_temp_cartera_historial', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Dui', 20)->nullable();
            $table->string('Nit', 20)->nullable();
            $table->string('Pasaporte', 50)->nullable();
            $table->string('CarnetResidencia', 50)->nullable();
            $table->string('Nacionalidad', 100)->nullable();
            $table->string('FechaNacimiento', 100)->nullable();
            $table->string('TipoPersona', 20)->nullable();
            $table->string('NombreCompleto', 200);
            $table->string('NombreSociedad', 200)->nullable();
            $table->string('Genero', 20)->nullable();
            $table->string('Direccion', 500)->nullable();
            $table->string('FechaOtorgamiento', 20)->nullable();
            $table->string('FechaVencimiento', 20)->nullable();
            $table->string('NumeroReferencia', 20)->nullable();
            $table->decimal('SumaAsegurada', 28, 14);
            $table->decimal('Tarifa', 4, 2)->nullable();
            $table->decimal('PrimaMensual', 28, 14)->nullable();
            $table->integer('NumeroCuotas')->nullable();
            $table->string('TipoDeuda', 100)->nullable();
            $table->string('ClaseCartera', 100)->nullable();
            $table->unsignedBigInteger('User');
            $table->integer('Axo')->nullable();
            $table->integer('Mes')->nullable();
            $table->integer('PolizaResidencia')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaFinal')->nullable();
            $table->mediumText('Errores')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_residencia_temp_cartera_historial');
    }
};
