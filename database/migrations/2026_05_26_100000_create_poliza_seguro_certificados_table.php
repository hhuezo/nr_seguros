<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_seguro_certificados', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaSeguroId');
            $table->integer('NumeroCertificado');
            $table->longText('DatosJson')->nullable();
            $table->text('Observacion')->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('PolizaSeguroId', 'fk_poliza_seguro_certificados_poliza')
                ->references('Id')
                ->on('poliza_seguro')
                ->onDelete('cascade');

            $table->unique(['PolizaSeguroId', 'NumeroCertificado'], 'uk_poliza_seguro_numero_certificado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_certificados');
    }
};
