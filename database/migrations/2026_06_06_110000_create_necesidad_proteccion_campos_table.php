<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('necesidad_proteccion_campos', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('NecesidadProteccion');
            $table->string('Etiqueta', 150);
            $table->string('NombreCampo', 150);
            $table->string('TipoCampo', 30)->default('text');
            $table->string('ValidacionCampo', 50)->default('ninguna');
            $table->string('Placeholder', 200)->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('NecesidadProteccion', 'fk_necesidad_proteccion_campo_ramo')
                ->references('Id')
                ->on('necesidad_proteccion')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('necesidad_proteccion_campos');
    }
};
