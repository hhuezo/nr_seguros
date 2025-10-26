<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_declarativa_control', function (Blueprint $table) {
            $table->bigIncrements('Id');

            // === Relaciones con distintas pólizas ===
            $table->unsignedBigInteger('PolizaDeudaId')->nullable();
            $table->foreign('PolizaDeudaId')
                ->references('Id')
                ->on('poliza_deuda')
                ->nullOnDelete()
                ->nullOnUpdate();

            $table->unsignedBigInteger('PolizaVidaId')->nullable();
            $table->foreign('PolizaVidaId')
                ->references('Id')
                ->on('poliza_vida')
                ->nullOnDelete()
                ->nullOnUpdate();

            $table->unsignedBigInteger('PolizaDesempleoId')->nullable();
            $table->foreign('PolizaDesempleoId')
                ->references('Id')
                ->on('poliza_desempleo')
                ->nullOnDelete()
                ->nullOnUpdate();

            $table->unsignedBigInteger('PolizaResidenciaId')->nullable();
            $table->foreign('PolizaResidenciaId')
                ->references('Id')
                ->on('poliza_residencia')
                ->nullOnDelete()
                ->nullOnUpdate();

            // === Campos adicionales de período ===
            $table->integer('Mes')->nullable();
            $table->integer('Axo')->nullable(); // Año (4 dígitos)

            // === Campos solicitados ===
            $table->date('FechaRecepcionArchivo')->nullable();
            $table->date('FechaEnvioACia')->nullable();
            $table->boolean('TrabajoEfectuadoDiaHabil')->nullable();
            $table->time('HoraTarea')->nullable();
            $table->string('FlujoAsignado', 150)->nullable();
            $table->decimal('PorcentajeRentabilidad', 8, 2)->nullable();
            $table->decimal('ValorDescuentoRentabilidad', 12, 2)->nullable();
            $table->string('AnexoDeclaracion', 255)->nullable();
            $table->string('NumeroACSisco', 100)->nullable();
            $table->date('FechaVencimiento')->nullable();
            $table->date('FechaEnvioACCliente')->nullable();

            // === FK Reproceso ===
            $table->unsignedBigInteger('ReprocesoNRId')->nullable();
            $table->foreign('ReprocesoNRId')
                ->references('Id')
                ->on('poliza_declarativa_reproceso')
                ->nullOnDelete()
                ->nullOnUpdate();

            $table->date('FechaEnvioCorreccion')->nullable();
            $table->date('FechaSeguimientoCobros')->nullable();
            $table->date('FechaRecepcionPago')->nullable();
            $table->date('FechaReporteACia')->nullable();
            $table->date('FechaAplicacion')->nullable();
            $table->text('Comentarios')->nullable();

            // === Auditoría ===
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_declarativa_control');
    }
};
