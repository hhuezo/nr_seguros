<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cobertura_tarificacion', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre', 100);
            $table->boolean('Activo')->default(true);
            // No se incluye $table->timestamps();
        });

        // Insertar los registros iniciales (sembrado de datos)
        DB::table('cobertura_tarificacion')->insert([
            ['Nombre' => 'Porcentual', 'Activo' => true],
            ['Nombre' => 'Millar', 'Activo' => true],
            ['Nombre' => 'Prima', 'Activo' => true],
            ['Nombre' => 'Sin Cobro de Prima', 'Activo' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobertura_tarificacion');
    }
};
