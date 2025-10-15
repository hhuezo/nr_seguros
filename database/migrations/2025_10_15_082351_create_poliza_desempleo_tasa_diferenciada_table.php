<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poliza_desempleo_tasa_diferenciada', function (Blueprint $table) {
            $table->bigIncrements('Id'); // BIGINT UNSIGNED PRIMARY KEY
            $table->unsignedBigInteger('PolizaDesempleoTipoCartera');
            $table->date('FechaDesde')->nullable();
            $table->date('FechaHasta')->nullable();
            $table->decimal('MontoDesde', 28, 4)->nullable();
            $table->decimal('MontoHasta', 28, 4)->nullable();
            $table->decimal('Tasa', 10, 5)->nullable();
            $table->unsignedBigInteger('Usuario')->nullable();

            // ✅ Relación perfectamente compatible
            $table->foreign('PolizaDesempleoTipoCartera', 'fk_tasa_tipo_cartera')
                ->references('Id')
                ->on('poliza_desempleo_tipo_cartera')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poliza_desempleo_tasa_diferenciada');
    }
};
