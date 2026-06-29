<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('poliza_seguro_cobertura');
        Schema::dropIfExists('poliza_seguro_datos_tecnicos');
    }

    public function down(): void
    {
        if (!Schema::hasTable('poliza_seguro_cobertura')) {
            Schema::create('poliza_seguro_cobertura', function (Blueprint $table) {
                $table->bigIncrements('Id');
                $table->unsignedBigInteger('PolizaSeguroId');
                $table->string('Nombre', 150)->nullable();
                $table->boolean('Tarificacion')->nullable();
                $table->boolean('Descuento')->nullable();
                $table->boolean('Iva')->nullable();
                $table->integer('Activo')->default(1)->nullable();
                $table->index('PolizaSeguroId');
            });
        }

        if (!Schema::hasTable('poliza_seguro_datos_tecnicos')) {
            Schema::create('poliza_seguro_datos_tecnicos', function (Blueprint $table) {
                $table->bigIncrements('Id');
                $table->unsignedBigInteger('PolizaSeguroId');
                $table->string('Nombre', 100);
                $table->string('Descripcion', 255)->nullable();
                $table->text('Valor')->nullable();
                $table->integer('Activo')->default(1)->nullable();
                $table->index('PolizaSeguroId');
            });
        }
    }
};
