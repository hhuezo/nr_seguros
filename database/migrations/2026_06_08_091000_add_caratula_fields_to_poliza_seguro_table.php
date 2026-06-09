<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro', 'SumaAsegurada')) {
                $table->decimal('SumaAsegurada', 14, 2)->nullable()->after('Planes');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PrimaNetaAnual')) {
                $table->decimal('PrimaNetaAnual', 14, 2)->nullable()->after('SumaAsegurada');
            }

            if (!Schema::hasColumn('poliza_seguro', 'NumCuotas')) {
                $table->integer('NumCuotas')->nullable()->after('FormaPago');
            }

            if (!Schema::hasColumn('poliza_seguro', 'PorcentajeComisionNR')) {
                $table->decimal('PorcentajeComisionNR', 8, 4)->nullable()->after('PrimaNetaAnual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            foreach (['SumaAsegurada', 'PrimaNetaAnual', 'NumCuotas', 'PorcentajeComisionNR'] as $column) {
                if (Schema::hasColumn('poliza_seguro', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
