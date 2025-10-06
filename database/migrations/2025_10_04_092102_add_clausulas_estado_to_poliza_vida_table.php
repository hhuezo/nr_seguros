<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_vida', function (Blueprint $table) {
            $table->string('Beneficios', 255)->nullable();
            $table->string('ClausulasEspeciales', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_vida', function (Blueprint $table) {
            $table->dropColumn(['Beneficios','ClausulasEspeciales']);
        });
    }
};
