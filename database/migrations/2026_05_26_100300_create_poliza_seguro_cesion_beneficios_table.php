<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaSeguroId');
            $table->tinyInteger('TieneCesion')->default(0);
            $table->string('Cesionario', 200)->nullable();
            $table->string('Documento', 100)->nullable();
            $table->decimal('Porcentaje', 8, 2)->nullable();
            $table->text('Observacion')->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('PolizaSeguroId', 'fk_poliza_seguro_cesion_poliza')
                ->references('Id')
                ->on('poliza_seguro')
                ->onDelete('cascade');

            $table->unique('PolizaSeguroId', 'uk_poliza_seguro_cesion_poliza');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_cesion_beneficios');
    }
};
