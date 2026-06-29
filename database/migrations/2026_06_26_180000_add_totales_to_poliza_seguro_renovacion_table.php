<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_renovacion', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_renovacion', 'SumaAsegurada')) {
                $table->decimal('SumaAsegurada', 14, 2)->nullable()->after('TarifaPlan');
            }

            if (!Schema::hasColumn('poliza_seguro_renovacion', 'PrimaNetaAnual')) {
                $table->decimal('PrimaNetaAnual', 14, 2)->nullable()->after('SumaAsegurada');
            }
        });

        DB::table('poliza_seguro_renovacion')
            ->select(['Id', 'TipoRenovacion', 'DatosPolizaJson'])
            ->orderBy('Id')
            ->chunkById(100, function ($registros) {
                foreach ($registros as $registro) {
                    $snapshot = json_decode($registro->DatosPolizaJson ?? '[]', true);
                    $snapshot = is_array($snapshot) ? $snapshot : [];
                    $esEmision = strtoupper((string) $registro->TipoRenovacion) === 'EMISION';

                    $sumaAsegurada = $snapshot['SumaAsegurada'] ?? null;
                    $primaNetaAnual = $snapshot['PrimaNetaAnual'] ?? null;

                    $sumaAsegurada = $esEmision
                        ? 0.0
                        : (is_numeric(str_replace(',', '', (string) $sumaAsegurada))
                        ? (float) str_replace(',', '', (string) $sumaAsegurada)
                        : null);

                    $primaNetaAnual = $esEmision
                        ? 0.0
                        : (is_numeric(str_replace(',', '', (string) $primaNetaAnual))
                        ? (float) str_replace(',', '', (string) $primaNetaAnual)
                        : null);

                    DB::table('poliza_seguro_renovacion')
                        ->where('Id', $registro->Id)
                        ->update([
                            'SumaAsegurada' => $sumaAsegurada,
                            'PrimaNetaAnual' => $primaNetaAnual,
                        ]);
                }
            }, 'Id');
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_renovacion', function (Blueprint $table) {
            foreach (['PrimaNetaAnual', 'SumaAsegurada'] as $column) {
                if (Schema::hasColumn('poliza_seguro_renovacion', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
