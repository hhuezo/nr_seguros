<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE plan_cobertura_detalle MODIFY Tasa DECIMAL(12,6) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE plan_cobertura_detalle MODIFY Tasa DECIMAL(8,3) NULL');
    }
};
