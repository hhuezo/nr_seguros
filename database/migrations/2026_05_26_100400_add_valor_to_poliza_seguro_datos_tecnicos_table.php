<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('poliza_seguro_datos_tecnicos', 'Valor')) {
            Schema::table('poliza_seguro_datos_tecnicos', function (Blueprint $table) {
                $table->text('Valor')->nullable()->after('Descripcion');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('poliza_seguro_datos_tecnicos', 'Valor')) {
            Schema::table('poliza_seguro_datos_tecnicos', function (Blueprint $table) {
                $table->dropColumn('Valor');
            });
        }
    }
};
