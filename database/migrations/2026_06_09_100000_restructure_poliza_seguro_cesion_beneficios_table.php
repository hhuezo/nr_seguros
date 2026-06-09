<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'poliza_seguro_cesion_beneficios'
              AND COLUMN_NAME = 'PolizaSeguroId'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $foreignKey) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios DROP FOREIGN KEY ' . $foreignKey->CONSTRAINT_NAME);
        }

        $index = DB::select("SHOW INDEX FROM poliza_seguro_cesion_beneficios WHERE Key_name = 'uk_poliza_seguro_cesion_poliza'");
        if (!empty($index)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios DROP INDEX uk_poliza_seguro_cesion_poliza');
        }

        $normalIndex = DB::select("SHOW INDEX FROM poliza_seguro_cesion_beneficios WHERE Key_name = 'idx_poliza_seguro_cesion_poliza'");
        if (empty($normalIndex)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios ADD INDEX idx_poliza_seguro_cesion_poliza (PolizaSeguroId)');
        }

        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'CodigoSesion')) {
                $table->string('CodigoSesion', 100)->nullable()->after('PolizaSeguroId');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Beneficiario')) {
                $table->string('Beneficiario', 200)->nullable()->after('CodigoSesion');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'FechaVigencia')) {
                $table->date('FechaVigencia')->nullable()->after('Beneficiario');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'FechaCancelacion')) {
                $table->date('FechaCancelacion')->nullable()->after('FechaVigencia');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'SumaCedida')) {
                $table->decimal('SumaCedida', 12, 2)->nullable()->after('FechaCancelacion');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Observaciones')) {
                $table->text('Observaciones')->nullable()->after('SumaCedida');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Propietario')) {
                $table->string('Propietario', 200)->nullable()->after('Observaciones');
            }
        });

        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['TieneCesion', 'Cesionario', 'Documento', 'Porcentaje', 'Observacion'] as $column) {
                if (Schema::hasColumn('poliza_seguro_cesion_beneficios', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'poliza_seguro_cesion_beneficios'
              AND COLUMN_NAME = 'PolizaSeguroId'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (empty($foreignKeys)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios ADD CONSTRAINT fk_poliza_seguro_cesion_poliza FOREIGN KEY (PolizaSeguroId) REFERENCES poliza_seguro (Id) ON DELETE CASCADE');
        }
    }

    public function down(): void
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'poliza_seguro_cesion_beneficios'
              AND COLUMN_NAME = 'PolizaSeguroId'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $foreignKey) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios DROP FOREIGN KEY ' . $foreignKey->CONSTRAINT_NAME);
        }

        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'TieneCesion')) {
                $table->tinyInteger('TieneCesion')->default(0)->after('PolizaSeguroId');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Cesionario')) {
                $table->string('Cesionario', 200)->nullable()->after('TieneCesion');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Documento')) {
                $table->string('Documento', 100)->nullable()->after('Cesionario');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Porcentaje')) {
                $table->decimal('Porcentaje', 8, 2)->nullable()->after('Documento');
            }
            if (!Schema::hasColumn('poliza_seguro_cesion_beneficios', 'Observacion')) {
                $table->text('Observacion')->nullable()->after('Porcentaje');
            }
        });

        Schema::table('poliza_seguro_cesion_beneficios', function (Blueprint $table) {
            $dropColumns = [];
            foreach (['CodigoSesion', 'Beneficiario', 'FechaVigencia', 'FechaCancelacion', 'SumaCedida', 'Observaciones', 'Propietario'] as $column) {
                if (Schema::hasColumn('poliza_seguro_cesion_beneficios', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        $index = DB::select("SHOW INDEX FROM poliza_seguro_cesion_beneficios WHERE Key_name = 'uk_poliza_seguro_cesion_poliza'");
        if (empty($index)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios ADD UNIQUE uk_poliza_seguro_cesion_poliza (PolizaSeguroId)');
        }

        $normalIndex = DB::select("SHOW INDEX FROM poliza_seguro_cesion_beneficios WHERE Key_name = 'idx_poliza_seguro_cesion_poliza'");
        if (!empty($normalIndex)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios DROP INDEX idx_poliza_seguro_cesion_poliza');
        }

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'poliza_seguro_cesion_beneficios'
              AND COLUMN_NAME = 'PolizaSeguroId'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (empty($foreignKeys)) {
            DB::statement('ALTER TABLE poliza_seguro_cesion_beneficios ADD CONSTRAINT fk_poliza_seguro_cesion_poliza FOREIGN KEY (PolizaSeguroId) REFERENCES poliza_seguro (Id) ON DELETE CASCADE');
        }
    }
};
