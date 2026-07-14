<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('poliza_seguro_certificado_coberturas', 'PorcentajeSuma')) {
            // La cobertura del certificado requiere porcentajes con 6 decimales para evitar redondeos.
            DB::statement('ALTER TABLE poliza_seguro_certificado_coberturas MODIFY PorcentajeSuma DECIMAL(10,6) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('poliza_seguro_certificado_coberturas', 'PorcentajeSuma')) {
            DB::statement('ALTER TABLE poliza_seguro_certificado_coberturas MODIFY PorcentajeSuma DECIMAL(8,4) NULL');
        }
    }
};
