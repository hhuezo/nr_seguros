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
        Schema::create('poliza_deuda_detalle_preliminar', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaDeudaId');
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
            $table->string('Usuario', 100)->nullable();
            $table->integer('UsuariosReportados')->nullable();
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('PolizaDeudaId');
            $table->index(['Axo', 'Mes']);

            // Foreign key si existe la tabla poliza_deuda
            $table->foreign('PolizaDeudaId')->references('Id')->on('poliza_deuda')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poliza_deuda_detalle_preliminar');
    }
};
