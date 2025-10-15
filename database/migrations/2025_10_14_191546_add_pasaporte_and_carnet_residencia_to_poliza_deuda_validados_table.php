<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_deuda_validados', function (Blueprint $table) {
            // 🔹 Agregar campos (pueden ser nulos)
            $table->string('Pasaporte', 30)->nullable()->after('Dui');
            $table->string('CarnetResidencia', 30)->nullable()->after('Pasaporte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poliza_deuda_validados', function (Blueprint $table) {
            $table->dropColumn(['Pasaporte', 'CarnetResidencia']);
        });
    }
};
