<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poliza_declarativa_reproceso', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre', 150);
            $table->boolean('Activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_declarativa_reproceso');
    }
};
