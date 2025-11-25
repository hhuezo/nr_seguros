<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ------------------------------------------------------
        // RENOMBRAR TABLAS si existen
        // ------------------------------------------------------
        if (Schema::hasTable('cobertura')) {
            Schema::rename('cobertura', 'producto_cobertura');
        }

        if (Schema::hasTable('cobertura_tarificacion')) {
            Schema::rename('cobertura_tarificacion', 'producto_cobertura_tarificacion');
        }

        if (Schema::hasTable('datos_tecnicos')) {
            Schema::rename('datos_tecnicos', 'producto_datos_tecnicos');
        }

        // ------------------------------------------------------
        // RENOMBRAR COLUMNAS EN producto_cobertura
        // ------------------------------------------------------
        if (Schema::hasTable('producto_cobertura')) {

            if (Schema::hasColumn('producto_cobertura', 'Producto')) {
                DB::statement("
                    ALTER TABLE producto_cobertura
                    CHANGE `Producto` `ProductoId` BIGINT(20) UNSIGNED NOT NULL
                ");
            }

            if (Schema::hasColumn('producto_cobertura', 'Tarificacion')) {
                DB::statement("
                    ALTER TABLE producto_cobertura
                    CHANGE `Tarificacion` `TarificacionId` BIGINT(20) UNSIGNED NOT NULL
                ");
            }
        }

        // ------------------------------------------------------
        // RENOMBRAR COLUMNAS EN producto_datos_tecnicos
        // ------------------------------------------------------
        if (Schema::hasTable('producto_datos_tecnicos')) {

            if (Schema::hasColumn('producto_datos_tecnicos', 'Producto')) {
                DB::statement("
                    ALTER TABLE producto_datos_tecnicos
                    CHANGE `Producto` `ProductoId` BIGINT(20) UNSIGNED NOT NULL
                ");
            }
        }
    }

    public function down(): void
    {
        // ------------------------------------------------------
        // REVERTIR COLUMNAS EN producto_cobertura
        // ------------------------------------------------------
        if (Schema::hasTable('producto_cobertura')) {

            if (Schema::hasColumn('producto_cobertura', 'ProductoId')) {
                DB::statement("
                    ALTER TABLE producto_cobertura
                    CHANGE `ProductoId` `Producto` BIGINT(20) UNSIGNED NOT NULL
                ");
            }

            if (Schema::hasColumn('producto_cobertura', 'TarificacionId')) {
                DB::statement("
                    ALTER TABLE producto_cobertura
                    CHANGE `TarificacionId` `Tarificacion` BIGINT(20) UNSIGNED NOT NULL
                ");
            }
        }

        // ------------------------------------------------------
        // REVERTIR COLUMNAS EN producto_datos_tecnicos
        // ------------------------------------------------------
        if (Schema::hasTable('producto_datos_tecnicos')) {

            if (Schema::hasColumn('producto_datos_tecnicos', 'ProductoId')) {
                DB::statement("
                    ALTER TABLE producto_datos_tecnicos
                    CHANGE `ProductoId` `Producto` BIGINT(20) UNSIGNED NOT NULL
                ");
            }
        }

        // ------------------------------------------------------
        // REVERTIR RENOMBRAR TABLAS
        // ------------------------------------------------------
        if (Schema::hasTable('producto_cobertura')) {
            Schema::rename('producto_cobertura', 'cobertura');
        }

        if (Schema::hasTable('producto_cobertura_tarificacion')) {
            Schema::rename('producto_cobertura_tarificacion', 'cobertura_tarificacion');
        }

        if (Schema::hasTable('producto_datos_tecnicos')) {
            Schema::rename('producto_datos_tecnicos', 'datos_tecnicos');
        }
    }
};
