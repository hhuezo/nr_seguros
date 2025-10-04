<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::table('poliza_desempleo', function (Blueprint $table) {
            $table->string('Concepto', 255)->nullable();
            $table->string('Beneficios', 255)->nullable();
            $table->string('ClausulasEspeciales', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('poliza_desempleo', function (Blueprint $table) {
            $table->dropColumn(['Concepto','Beneficios', 'ClausulasEspeciales']);
        });
    }
};
