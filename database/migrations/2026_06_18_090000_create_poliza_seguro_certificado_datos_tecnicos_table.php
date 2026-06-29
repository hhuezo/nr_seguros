<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('poliza_seguro_certificado_datos_tecnicos')) {
            Schema::create('poliza_seguro_certificado_datos_tecnicos', function (Blueprint $table) {
                $table->bigIncrements('Id');
                $table->unsignedBigInteger('PolizaSeguroCertificadoId');
                $table->unsignedBigInteger('DatoTecnicoId')->nullable();
                $table->string('Nombre', 150);
                $table->string('Descripcion', 255)->nullable();
                $table->text('Valor')->nullable();
                $table->boolean('Activo')->default(1);

                $table->foreign('PolizaSeguroCertificadoId', 'fk_cert_datos_tecnicos_certificado')
                    ->references('Id')
                    ->on('poliza_seguro_certificados')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_certificado_datos_tecnicos');
    }
};
