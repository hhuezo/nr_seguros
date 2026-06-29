<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cesionario', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre', 200);
            $table->tinyInteger('Activo')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cesionario');
    }
};
