<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiar Plan → PlanId (MariaDB compatible)
        DB::statement("
            ALTER TABLE plan_cobertura_detalle
            CHANGE `Plan` `PlanId` BIGINT(20) UNSIGNED NOT NULL
        ");
    }

    public function down(): void
    {
        // Revertir PlanId → Plan
        DB::statement("
            ALTER TABLE plan_cobertura_detalle
            CHANGE `PlanId` `Plan` BIGINT(20) UNSIGNED NOT NULL
        ");
    }
};
