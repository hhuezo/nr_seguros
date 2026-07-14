<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plan_cobertura_detalle', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_cobertura_detalle', 'Tarificacion')) {
                $table->unsignedBigInteger('Tarificacion')->nullable()->after('Cobertura');
            }

            if (!Schema::hasColumn('plan_cobertura_detalle', 'TarificacionNombre')) {
                // Snapshot de la tarificacion definida en producto al configurar la cobertura del plan.
                $table->string('TarificacionNombre', 100)->nullable()->after('Tarificacion');
            }

            if (!Schema::hasColumn('plan_cobertura_detalle', 'CoberturaPrincipal')) {
                $table->tinyInteger('CoberturaPrincipal')->default(0)->after('Prima');
            }
        });

        DB::statement("
            UPDATE plan_cobertura_detalle detalle
            INNER JOIN cobertura cobertura ON cobertura.Id = detalle.Cobertura
            LEFT JOIN cobertura_tarificacion tarificacion ON tarificacion.Id = cobertura.Tarificacion
            SET detalle.Tarificacion = cobertura.Tarificacion,
                detalle.TarificacionNombre = tarificacion.Nombre
            WHERE detalle.Tarificacion IS NULL
        ");

        // Si un plan solo tiene una cobertura activa, esa cobertura es una referencia segura como principal.
        DB::statement("
            UPDATE plan_cobertura_detalle detalle
            INNER JOIN (
                SELECT Plan, MIN(Cobertura) AS Cobertura, COUNT(*) AS Total
                FROM plan_cobertura_detalle
                WHERE Activo = 1
                GROUP BY Plan
            ) resumen ON resumen.Plan = detalle.Plan
                AND resumen.Cobertura = detalle.Cobertura
                AND resumen.Total = 1
            SET detalle.CoberturaPrincipal = 1
            WHERE detalle.CoberturaPrincipal = 0
        ");
    }

    public function down(): void
    {
        Schema::table('plan_cobertura_detalle', function (Blueprint $table) {
            foreach (['CoberturaPrincipal', 'TarificacionNombre', 'Tarificacion'] as $column) {
                if (Schema::hasColumn('plan_cobertura_detalle', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
