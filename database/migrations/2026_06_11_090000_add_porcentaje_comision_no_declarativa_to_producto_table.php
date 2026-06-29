<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            if (!Schema::hasColumn('producto', 'PorcentajeComisionNoDeclarativa')) {
                $table->decimal('PorcentajeComisionNoDeclarativa', 10, 4)->nullable()->after('Descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            if (Schema::hasColumn('producto', 'PorcentajeComisionNoDeclarativa')) {
                $table->dropColumn('PorcentajeComisionNoDeclarativa');
            }
        });
    }
};
