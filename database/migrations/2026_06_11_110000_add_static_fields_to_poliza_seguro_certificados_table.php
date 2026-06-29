<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'CertificadoAseguradora')) {
                $table->string('CertificadoAseguradora', 100)->nullable()->after('NumeroCertificado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'CodAsegurado')) {
                $table->string('CodAsegurado', 100)->nullable()->after('CertificadoAseguradora');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'FechaInclusion')) {
                $table->date('FechaInclusion')->nullable()->after('VigenciaHasta');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'DiasVigencia')) {
                $table->integer('DiasVigencia')->nullable()->after('FechaInclusion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Deducible')) {
                $table->string('Deducible', 200)->nullable()->after('Estado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Participacion')) {
                $table->string('Participacion', 200)->nullable()->after('Deducible');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PorcentajeDepreciacion')) {
                $table->decimal('PorcentajeDepreciacion', 8, 4)->nullable()->after('Participacion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PrimaMinima')) {
                $table->decimal('PrimaMinima', 14, 2)->nullable()->after('PorcentajeDepreciacion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'MotivoExclusion')) {
                $table->string('MotivoExclusion', 200)->nullable()->after('PrimaMinima');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'FechaExclusion')) {
                $table->date('FechaExclusion')->nullable()->after('MotivoExclusion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'UsuarioModifica')) {
                $table->unsignedBigInteger('UsuarioModifica')->nullable()->after('FechaExclusion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'FechaModificacion')) {
                $table->dateTime('FechaModificacion')->nullable()->after('UsuarioModifica');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Vendedor')) {
                $table->string('Vendedor', 200)->nullable()->after('FechaModificacion');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PrimaTotal')) {
                $table->decimal('PrimaTotal', 14, 2)->nullable()->after('ValorAsegurado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PorcentajeDescuentoRentabilidad')) {
                $table->decimal('PorcentajeDescuentoRentabilidad', 8, 4)->nullable()->after('PrimaTotal');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'ValorDescuento')) {
                $table->decimal('ValorDescuento', 14, 2)->nullable()->after('PorcentajeDescuentoRentabilidad');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PrimaExenta')) {
                $table->decimal('PrimaExenta', 14, 2)->nullable()->after('PrimaNeta');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'GastosEmision')) {
                $table->decimal('GastosEmision', 14, 2)->nullable()->after('PrimaExenta');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'GastosFraccionamiento')) {
                $table->decimal('GastosFraccionamiento', 14, 2)->nullable()->after('GastosEmision');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'GastosBomberos')) {
                $table->decimal('GastosBomberos', 14, 2)->nullable()->after('GastosFraccionamiento');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'OtrosGastos')) {
                $table->decimal('OtrosGastos', 14, 2)->nullable()->after('GastosBomberos');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Impuestos')) {
                $table->decimal('Impuestos', 14, 2)->nullable()->after('OtrosGastos');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'TotalCertificado')) {
                $table->decimal('TotalCertificado', 14, 2)->nullable()->after('Impuestos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            foreach ([
                'TotalCertificado',
                'Impuestos',
                'OtrosGastos',
                'GastosBomberos',
                'GastosFraccionamiento',
                'GastosEmision',
                'PrimaExenta',
                'ValorDescuento',
                'PorcentajeDescuentoRentabilidad',
                'PrimaTotal',
                'Vendedor',
                'FechaModificacion',
                'UsuarioModifica',
                'FechaExclusion',
                'MotivoExclusion',
                'PrimaMinima',
                'PorcentajeDepreciacion',
                'Participacion',
                'Deducible',
                'DiasVigencia',
                'FechaInclusion',
                'CodAsegurado',
                'CertificadoAseguradora',
            ] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
