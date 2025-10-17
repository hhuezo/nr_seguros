<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar la tabla si ya existe
        Schema::dropIfExists('poliza_desempleo_cartera');

        // Crear nuevamente la tabla
        Schema::create('poliza_desempleo_cartera', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaDesempleo');
            $table->string('Nit', 30)->nullable();
            $table->string('Dui', 20)->nullable();
            $table->string('Pasaporte', 50)->nullable();
            $table->string('Nacionalidad', 100)->nullable();
            $table->string('FechaNacimiento', 20)->nullable();
            $table->string('TipoPersona', 20)->nullable();
            $table->string('PrimerApellido', 50)->nullable();
            $table->string('SegundoApellido', 100)->nullable();
            $table->string('ApellidoCasada', 200)->nullable();
            $table->string('PrimerNombre', 200)->nullable();
            $table->string('SegundoNombre', 50)->nullable();
            $table->string('NombreSociedad', 200)->nullable();
            $table->char('Sexo', 1)->nullable();
            $table->string('FechaOtorgamiento', 20)->nullable();
            $table->string('FechaVencimiento', 20)->nullable();
            $table->string('Ocupacion', 100)->nullable();
            $table->string('NumeroReferencia', 255)->nullable();

            $table->decimal('MontoOtorgado', 28, 14)->nullable();
            $table->decimal('SaldoCapital', 28, 14)->nullable();
            $table->decimal('Intereses', 28, 14)->nullable();
            $table->decimal('MoraCapital', 28, 14)->nullable();
            $table->decimal('InteresesMoratorios', 28, 14)->nullable();
            $table->decimal('SaldoTotal', 28, 14)->nullable();
            $table->unsignedBigInteger('User');

            $table->integer('Axo')->nullable();
            $table->integer('Mes')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaFinal')->nullable();
            $table->integer('TipoError')->default(0);

            $table->date('FechaNacimientoDate')->nullable();
            $table->integer('Edad')->nullable();
            $table->integer('EdadDesembloso')->nullable();

            $table->decimal('InteresesCovid', 28, 14)->nullable();
            $table->decimal('MontoNominal', 28, 14)->nullable();

            $table->integer('NoValido')->default(0);
            $table->date('FechaOtorgamientoDate')->nullable();
            $table->integer('Excluido')->default(0);
            $table->integer('Rehabilitado')->default(0);
            $table->integer('EdadRequisito')->nullable();

            $table->unsignedBigInteger('PolizaDesempleoDetalle')->nullable();
            $table->string('CarnetResidencia', 30)->nullable();
            $table->integer('DesempleoTipoCartera')->nullable();

            // Índices
            $table->index('PolizaDesempleo', 'fk_desempleo_cartera_poliza_idx');
            $table->index('User', 'fk_desempleo_cartera_usuario_idx');
            $table->index('PolizaDesempleoDetalle', 'fk_desempleo_cartera_detalle_idx');

            // Llaves foráneas
            $table->foreign('PolizaDesempleo')
                ->references('Id')->on('poliza_desempleo')
                ->onDelete('no action')->onUpdate('no action');

            $table->foreign('User')
                ->references('id')->on('users')
                ->onDelete('no action')->onUpdate('no action');

            $table->foreign('PolizaDesempleoDetalle')
                ->references('Id')->on('poliza_deuda_detalle')
                ->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_desempleo_cartera');
    }
};
