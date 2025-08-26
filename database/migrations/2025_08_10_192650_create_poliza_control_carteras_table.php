<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('poliza_control_carteras', function (Blueprint $table) {
            $table->id();

            $table->integer('Axo'); // año
            $table->string('Mes', 2);

            // Foreign keys con tipo int unsigned
            $table->unsignedInteger('DeudaId')->nullable();
            $table->unsignedInteger('DesempleoId')->nullable();
            $table->unsignedInteger('ResidenciaId')->nullable();
            $table->unsignedInteger('VidaId')->nullable();

            $table->date('FechaRecepcionArchivo')->nullable();
            $table->date('FechaEnvioCia')->nullable();
            $table->integer('TrabajoEfectuado')->nullable();
            $table->time('HoraTarea')->nullable();
            $table->string('FlujoAsignado')->nullable();
            $table->integer('Usuario')->nullable();
            $table->integer('UsuariosReportados')->nullable();

            $table->decimal('Tarifa', 15, 2)->nullable();
            $table->decimal('PrimaBruta', 15, 2)->nullable();
            $table->decimal('ExtraPrima', 15, 2)->nullable();
            $table->decimal('PrimaEmitida', 15, 2)->nullable();


            $table->decimal('PorcentajeComision', 10, 4)->nullable();
            $table->decimal('ComisionNeta', 15, 5)->nullable();
            $table->decimal('Iva', 15, 5)->nullable();
            $table->decimal('PrimaLiquida', 15, 2)->nullable();
            $table->integer('AnexoDeclaracion')->nullable(); //select
            $table->string('NumeroCisco')->nullable();
            $table->date('FechaVencimiento')->nullable();
            $table->integer('RepocesoNr')->nullable(); //select
            $table->date('FechaEnvioCorreccion')->nullable();
            $table->date('FechaSeguimientoCobro')->nullable();
            $table->date('FechaReporteCia')->nullable();
            $table->date('FechaAplicacion')->nullable();
            $table->string('Comentarios')->nullable();

            // Definir las foreign keys
            $table->foreign('DeudaId')->references('Id')->on('poliza_deuda')->onDelete('set null');
            $table->foreign('DesempleoId')->references('Id')->on('poliza_desempleo')->onDelete('set null');
            $table->foreign('ResidenciaId')->references('Id')->on('poliza_residencia')->onDelete('set null');
            $table->foreign('VidaId')->references('Id')->on('poliza_vida')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('poliza_control_carteras');
    }
};
