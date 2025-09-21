<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aseguradora', function (Blueprint $table) {
            $table->increments('Id');

            $table->string('Nit', 100)->nullable();
            $table->string('RegistroFiscal', 100)->nullable();
            $table->string('Nombre', 255)->nullable();
            $table->string('Abreviatura', 100)->nullable();
            $table->date('FechaVinculacion')->nullable();
            $table->integer('TipoContribuyente')->nullable();
            $table->string('PaginaWeb', 100)->nullable();
            $table->date('FechaConstitucion')->nullable();
            $table->string('Direccion', 255)->nullable();
            $table->string('TelefonoFijo', 10)->nullable();
            $table->string('TelefonoWhatsapp', 10)->nullable();
            $table->integer('Activo')->default(1);
            $table->string('Codigo', 10)->nullable();
            $table->integer('Distrito')->nullable();
            $table->integer('Diario')->nullable();
            $table->integer('Dias365')->default(0);

            // FOREIGN KEY
            $table->foreign('TipoContribuyente')
                ->references('Id')
                ->on('tipo_contribuyente')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aseguradora');
    }
};
