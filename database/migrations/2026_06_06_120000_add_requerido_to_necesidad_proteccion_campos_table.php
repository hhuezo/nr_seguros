<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('necesidad_proteccion_campos', 'Requerido')) {
            Schema::table('necesidad_proteccion_campos', function (Blueprint $table) {
                $table->tinyInteger('Requerido')->default(1)->after('ValidacionCampo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('necesidad_proteccion_campos', 'Requerido')) {
            Schema::table('necesidad_proteccion_campos', function (Blueprint $table) {
                $table->dropColumn('Requerido');
            });
        }
    }
};
