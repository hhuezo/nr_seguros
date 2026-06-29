<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro', 'NumeroVigencia')) {
                $table->integer('NumeroVigencia')->nullable()->after('Oferta');
            }
        });

        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'Asegurado')) {
                $table->string('Asegurado', 200)->nullable()->after('NumeroCertificado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'VigenciaDesde')) {
                $table->date('VigenciaDesde')->nullable()->after('Asegurado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'VigenciaHasta')) {
                $table->date('VigenciaHasta')->nullable()->after('VigenciaDesde');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'ValorAsegurado')) {
                $table->decimal('ValorAsegurado', 14, 2)->nullable()->after('VigenciaHasta');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'PrimaNeta')) {
                $table->decimal('PrimaNeta', 14, 2)->nullable()->after('ValorAsegurado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'Estado')) {
                $table->string('Estado', 50)->nullable()->after('PrimaNeta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            foreach (['Estado', 'PrimaNeta', 'ValorAsegurado', 'VigenciaHasta', 'VigenciaDesde', 'Asegurado'] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro', 'NumeroVigencia')) {
                $table->dropColumn('NumeroVigencia');
            }
        });
    }
};
