<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaSeguroCertificadoId');
            $table->unsignedBigInteger('Cobertura')->nullable();
            $table->unsignedBigInteger('Tarificacion')->nullable();
            $table->string('TarificacionNombre', 100)->nullable();
            $table->string('Nombre', 250);
            $table->decimal('SumaAsegurada', 14, 2)->nullable();
            $table->decimal('PorcentajeSuma', 10, 6)->nullable();
            $table->decimal('Tasa', 12, 6)->nullable();
            $table->decimal('Prima', 14, 2)->nullable();
            $table->decimal('PorcentajeDeducible', 8, 4)->nullable();
            $table->string('Deducible', 200)->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('PolizaSeguroCertificadoId', 'fk_certificado_cobertura_certificado')
                ->references('Id')
                ->on('poliza_seguro_certificados')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_certificado_coberturas');
    }
};
