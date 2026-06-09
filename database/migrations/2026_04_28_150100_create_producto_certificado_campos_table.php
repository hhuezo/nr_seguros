<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_certificado_campos', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('Producto');
            $table->string('Etiqueta', 150);
            $table->string('NombreCampo', 150);
            $table->string('TipoCampo', 30)->default('text');
            $table->tinyInteger('Requerido')->default(1);
            $table->integer('Orden')->default(1);
            $table->string('Placeholder', 200)->nullable();
            $table->text('Ayuda')->nullable();
            $table->text('OpcionesJson')->nullable();
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('Producto', 'fk_producto_certificado_campo_producto')
                ->references('Id')
                ->on('producto')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_certificado_campos');
    }
};

