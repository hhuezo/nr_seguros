<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'FechaNacimiento')) {
                $table->date('FechaNacimiento')->nullable()->after('Asegurado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Sexo')) {
                $table->char('Sexo', 1)->nullable()->after('FechaNacimiento');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            foreach (['Sexo', 'FechaNacimiento'] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
