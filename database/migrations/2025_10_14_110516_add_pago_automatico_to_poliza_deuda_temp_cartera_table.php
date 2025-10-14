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
        Schema::table('poliza_deuda_temp_cartera', function (Blueprint $table) {
            $table->integer('PagoAutomatico')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_deuda_temp_cartera', function (Blueprint $table) {
            $table->dropColumn('PagoAutomatico');
        });
    }
};
