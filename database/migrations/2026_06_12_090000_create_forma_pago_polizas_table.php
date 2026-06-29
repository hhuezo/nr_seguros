<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forma_pago_polizas', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre', 150);
            $table->tinyInteger('Activo')->default(1);
        });

        DB::table('forma_pago_polizas')->insert([
            ['Id' => 1, 'Nombre' => 'ANUAL', 'Activo' => 1],
            ['Id' => 2, 'Nombre' => 'SEMESTRAL', 'Activo' => 1],
            ['Id' => 3, 'Nombre' => 'TRIMESTRAL', 'Activo' => 1],
            ['Id' => 4, 'Nombre' => 'MENSUAL', 'Activo' => 1],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('forma_pago_polizas');
    }
};
