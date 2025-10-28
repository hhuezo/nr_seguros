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
        Schema::table('poliza_deuda_detalle', function (Blueprint $table) {
            $table->integer('Axo')->nullable()->after('Deuda');
            $table->string('Mes', 2)->nullable()->after('Axo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_deuda_detalle', function (Blueprint $table) {
            $table->dropColumn(['Axo', 'Mes']);
        });
    }
};
