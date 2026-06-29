<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro', 'TipoCarteraNR')) {
                $table->unsignedBigInteger('TipoCarteraNR')->nullable()->after('Departamento');
            }

            if (!Schema::hasColumn('poliza_seguro', 'IvaIncluido')) {
                $table->char('IvaIncluido', 1)->nullable()->after('PrimaNetaAnual');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PorcentajeDescuentoRentabilidad')) {
                $table->decimal('PorcentajeDescuentoRentabilidad', 8, 4)->nullable()->after('IvaIncluido');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PorcentajeDescuentoBuenaExperiencia')) {
                $table->decimal('PorcentajeDescuentoBuenaExperiencia', 8, 4)->nullable()->after('PorcentajeDescuentoRentabilidad');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PorcentajeOtrosDescuentos')) {
                $table->decimal('PorcentajeOtrosDescuentos', 8, 4)->nullable()->after('PorcentajeDescuentoBuenaExperiencia');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PorcentajeComsionCliente')) {
                $table->decimal('PorcentajeComsionCliente', 8, 4)->nullable()->after('PorcentajeOtrosDescuentos');
            }

            if (!Schema::hasColumn('poliza_seguro', 'ClausulasEspeciales')) {
                $table->text('ClausulasEspeciales')->nullable()->after('SustituidaPoliza');
            }

            if (!Schema::hasColumn('poliza_seguro', 'BeneficiosAdicionales')) {
                $table->text('BeneficiosAdicionales')->nullable()->after('ClausulasEspeciales');
            }

            if (!Schema::hasColumn('poliza_seguro', 'Comentarios')) {
                $table->text('Comentarios')->nullable()->after('BeneficiosAdicionales');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            foreach ([
                'Comentarios',
                'BeneficiosAdicionales',
                'ClausulasEspeciales',
                'PorcentajeComsionCliente',
                'PorcentajeOtrosDescuentos',
                'PorcentajeDescuentoBuenaExperiencia',
                'PorcentajeDescuentoRentabilidad',
                'IvaIncluido',
                'TipoCarteraNR',
            ] as $column) {
                if (Schema::hasColumn('poliza_seguro', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
