<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tablas auxiliares
        Schema::create('cliente_estado', function (Blueprint $table) {
            $table->increments('Id'); // unsigned por defecto
            $table->string('Nombre', 255);
        });

        Schema::create('tipo_contribuyente', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
        });

        Schema::create('ubicacion_cobro', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->integer('Activo')->default(1);
        });

        Schema::create('forma_pago', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 255);
            $table->integer('Activo')->default(1);
        });

        Schema::create('cliente_necesidad_proteccion', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->integer('Activo')->default(1);
        });

        Schema::create('cliente_informarse', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->integer('Activo')->default(1);
        });

        Schema::create('cliente_motivo_eleccion', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->integer('Activo')->default(1);
        });

        Schema::create('cliente_preferencia_compra', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->integer('Activo')->default(1);
        });

        // 2. Tabla cliente
        Schema::create('cliente', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nit', 20)->nullable();
            $table->string('Dui', 20)->nullable();
            $table->string('Nombre', 255);
            $table->string('RegistroFiscal', 255)->nullable();
            $table->date('FechaNacimiento')->nullable();
            $table->unsignedInteger('Estado')->nullable();
            $table->unsignedInteger('FormaPago')->nullable();
            $table->unsignedInteger('Informarse')->nullable();
            $table->unsignedInteger('MotivoEleccion')->nullable();
            $table->unsignedInteger('NecesidadProteccion')->nullable();
            $table->unsignedInteger('PreferenciaCompra')->nullable();
            $table->unsignedInteger('TipoContribuyente')->nullable();
            $table->unsignedInteger('UbicacionCobro')->nullable();
            $table->timestamps();

            // 3. Foreign keys
            $table->foreign('Estado')->references('Id')->on('cliente_estado')->onDelete('set null');
            $table->foreign('FormaPago')->references('Id')->on('forma_pago')->onDelete('set null');
            $table->foreign('Informarse')->references('Id')->on('cliente_informarse')->onDelete('set null');
            $table->foreign('MotivoEleccion')->references('Id')->on('cliente_motivo_eleccion')->onDelete('set null');
            $table->foreign('NecesidadProteccion')->references('Id')->on('cliente_necesidad_proteccion')->onDelete('set null');
            $table->foreign('PreferenciaCompra')->references('Id')->on('cliente_preferencia_compra')->onDelete('set null');
            $table->foreign('TipoContribuyente')->references('Id')->on('tipo_contribuyente')->onDelete('set null');
            $table->foreign('UbicacionCobro')->references('Id')->on('ubicacion_cobro')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
        Schema::dropIfExists('cliente_preferencia_compra');
        Schema::dropIfExists('cliente_motivo_eleccion');
        Schema::dropIfExists('cliente_informarse');
        Schema::dropIfExists('cliente_necesidad_proteccion');
        Schema::dropIfExists('forma_pago');
        Schema::dropIfExists('ubicacion_cobro');
        Schema::dropIfExists('tipo_contribuyente');
        Schema::dropIfExists('cliente_estado');
    }
};
