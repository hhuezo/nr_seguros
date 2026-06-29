<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'PorcentajeDescuentoBuenaExperiencia')) {
                $table->decimal('PorcentajeDescuentoBuenaExperiencia', 8, 4)->nullable()->after('ValorDescuento');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'ValorDescuentoBuenaExperiencia')) {
                $table->decimal('ValorDescuentoBuenaExperiencia', 14, 2)->nullable()->after('PorcentajeDescuentoBuenaExperiencia');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PorcentajeOtrosDescuentos')) {
                $table->decimal('PorcentajeOtrosDescuentos', 8, 4)->nullable()->after('ValorDescuentoBuenaExperiencia');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'ValorOtrosDescuentos')) {
                $table->decimal('ValorOtrosDescuentos', 14, 2)->nullable()->after('PorcentajeOtrosDescuentos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            foreach ([
                'ValorOtrosDescuentos',
                'PorcentajeOtrosDescuentos',
                'ValorDescuentoBuenaExperiencia',
                'PorcentajeDescuentoBuenaExperiencia',
            ] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
