<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('poliza_seguro_renovacion')) {
            return;
        }

        Schema::create('poliza_seguro_renovacion', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('PolizaSeguroId');
            $table->string('TipoRenovacion', 40)->default('RENOVACION');
            $table->unsignedInteger('EstadoPoliza')->nullable();
            $table->unsignedInteger('NumeroVigencia')->nullable();
            $table->date('VigenciaDesde')->nullable();
            $table->date('VigenciaHasta')->nullable();
            $table->string('TarifaPlan', 255)->nullable();
            $table->longText('DatosPolizaJson')->nullable();
            $table->longText('CambiosJson')->nullable();
            $table->unsignedInteger('Usuario')->nullable();
            $table->dateTime('FechaRegistro')->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->index('PolizaSeguroId', 'idx_poliza_seguro_renovacion_poliza');
            $table->index('EstadoPoliza', 'idx_poliza_seguro_renovacion_estado');
            $table->index('Usuario', 'idx_poliza_seguro_renovacion_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_renovacion');
    }
};
