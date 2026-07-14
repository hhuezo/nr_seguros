<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificado_coberturas', 'Tarificacion')) {
                $table->unsignedBigInteger('Tarificacion')->nullable()->after('Cobertura');
            }

            if (!Schema::hasColumn('poliza_seguro_certificado_coberturas', 'TarificacionNombre')) {
                // Snapshot del tipo de calculo usado por la cobertura al momento de guardar el certificado.
                $table->string('TarificacionNombre', 100)->nullable()->after('Tarificacion');
            }
        });

        DB::table('poliza_seguro_certificado_coberturas as certificado')
            ->join('cobertura as cobertura', 'cobertura.Id', '=', 'certificado.Cobertura')
            ->leftJoin('cobertura_tarificacion as tarificacion', 'tarificacion.Id', '=', 'cobertura.Tarificacion')
            ->whereNull('certificado.Tarificacion')
            ->update([
                'certificado.Tarificacion' => DB::raw('cobertura.Tarificacion'),
                'certificado.TarificacionNombre' => DB::raw('tarificacion.Nombre'),
            ]);
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            foreach (['TarificacionNombre', 'Tarificacion'] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificado_coberturas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
