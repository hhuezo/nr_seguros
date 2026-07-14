<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [];

        // El tab de sumas del certificado ya no administra deducibles por cobertura.
        foreach (['PorcentajeDeducible', 'Deducible'] as $column) {
            if (Schema::hasColumn('poliza_seguro_certificado_coberturas', $column)) {
                $columns[] = $column;
            }
        }

        if (!empty($columns)) {
            Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificado_coberturas', 'PorcentajeDeducible')) {
                $table->decimal('PorcentajeDeducible', 8, 4)->nullable()->after('Prima');
            }

            if (!Schema::hasColumn('poliza_seguro_certificado_coberturas', 'Deducible')) {
                $table->string('Deducible', 200)->nullable()->after('PorcentajeDeducible');
            }
        });
    }
};
