<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_seguro_certificado_dependientes', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaSeguroCertificadoId');
            $table->integer('NumeroDependiente');
            $table->longText('DatosJson')->nullable();
            $table->text('Observacion')->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('PolizaSeguroCertificadoId', 'fk_poliza_seguro_dependientes_certificado')
                ->references('Id')
                ->on('poliza_seguro_certificados')
                ->onDelete('cascade');

            $table->unique(['PolizaSeguroCertificadoId', 'NumeroDependiente'], 'uk_poliza_seguro_numero_dependiente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_certificado_dependientes');
    }
};
