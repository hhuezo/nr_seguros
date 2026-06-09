<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_seguro_beneficiarios', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('PolizaSeguroId');
            $table->string('Nombre', 200);
            $table->date('FechaNacimiento')->nullable();
            $table->decimal('Porcentaje', 8, 2)->default(0);
            $table->tinyInteger('Activo')->default(1);

            $table->foreign('PolizaSeguroId', 'fk_poliza_seguro_beneficiarios_poliza')
                ->references('Id')
                ->on('poliza_seguro')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_seguro_beneficiarios');
    }
};
