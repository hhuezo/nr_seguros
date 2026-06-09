<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->decimal('PorcentajeBomberos', 8, 4)->nullable()->after('ComisionBomberos');
        });
    }

    public function down(): void
    {
        Schema::table('necesidad_proteccion', function (Blueprint $table) {
            $table->dropColumn('PorcentajeBomberos');
        });
    }
};

