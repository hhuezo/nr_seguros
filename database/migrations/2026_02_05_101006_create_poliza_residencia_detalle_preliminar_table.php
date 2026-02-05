<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poliza_residencia_detalle_preliminar', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaResidenciaId');
            $table->integer('Axo');
            $table->integer('Mes');
            $table->decimal('MontoCartera', 18, 2)->nullable();
            $table->decimal('Tasa', 8, 4)->nullable();
            $table->decimal('PrimaCalculada', 18, 2)->nullable();
            $table->decimal('ExtraPrima', 18, 2)->nullable();
            $table->decimal('PrimaDescontada', 18, 2)->nullable();
            $table->decimal('TasaComision', 8, 4)->nullable();
            $table->decimal('Comision', 18, 2)->nullable();
            $table->decimal('Retencion', 18, 2)->nullable();
            $table->decimal('IvaSobreComision', 18, 2)->nullable();
            $table->decimal('Iva', 18, 2)->nullable();
            $table->decimal('APagar', 18, 2)->nullable();
            $table->string('NumeroRecibo', 100)->nullable();
            $table->date('FechaInicio')->nullable();
            $table->integer('Usuario')->nullable();
            $table->integer('UsuariosReportados')->nullable();
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('PolizaResidenciaId');
            $table->index(['Axo', 'Mes']);

            // Foreign key si existe la tabla poliza_residencia
            $table->foreign('PolizaResidenciaId')->references('Id')->on('poliza_residencia')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poliza_residencia_detalle_preliminar');
    }
};
