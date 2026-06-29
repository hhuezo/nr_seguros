<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estado_certificado', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre', 150);
            $table->tinyInteger('Activo')->default(1);
        });

        DB::table('estado_certificado')->insert([
            ['Nombre' => 'CERTIFICADO VIGENTE', 'Activo' => 1],
            ['Nombre' => 'CERTIFICADO CANCELADO', 'Activo' => 1],
            ['Nombre' => 'CERTIFICADO SUSPENDIDO', 'Activo' => 1],
            ['Nombre' => 'CERTIFICADO EXCLUIDO', 'Activo' => 1],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_certificado');
    }
};
