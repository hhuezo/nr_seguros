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
    public function up(): void
    {
        Schema::table('poliza_vida_detalle', function (Blueprint $table) {
            $table->integer('Axo')->nullable()->after('PolizaVida');
            $table->unsignedTinyInteger('Mes')->nullable()->after('Axo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_vida_detalle', function (Blueprint $table) {
            $table->dropColumn(['Axo', 'Mes']);
        });
    }
};
