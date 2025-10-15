<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_desempleo_tipo_cartera', function (Blueprint $table) {
            $table->bigIncrements('Id'); // BIGINT UNSIGNED PRIMARY KEY
            $table->unsignedBigInteger('PolizaDesempleo');
            $table->unsignedBigInteger('SaldosMontos');
            $table->integer('TipoCalculo')->default(1);

            // // Opcional: índices si se usan en joins frecuentes
            // $table->index('PolizaDesempleo');
            // $table->index('SaldosMontos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_desempleo_tipo_cartera');
    }
};
