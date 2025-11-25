<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Renombrar tablas
        Schema::rename('cobertura', 'producto_cobertura');
        Schema::rename('cobertura_tarificacion', 'producto_cobertura_tarificacion');
        Schema::rename('datos_tecnicos', 'producto_datos_tecnicos');

        // Renombrar columna en producto_cobertura
        DB::statement("ALTER TABLE producto_cobertura CHANGE `Producto` `ProductoId` BIGINT(20) UNSIGNED NOT NULL");

        // Renombrar columna en producto_datos_tecnicos
        DB::statement("ALTER TABLE producto_datos_tecnicos CHANGE `Producto` `ProductoId` BIGINT(20) UNSIGNED NOT NULL");

         // Renombrar columna en producto_cobertura_tarificacion
        DB::statement("ALTER TABLE producto_cobertura_tarificacion CHANGE `Cobertura` `CoberturaId` BIGINT(20) UNSIGNED NOT NULL");
    }

    public function down(): void
    {
        // Revertir columnas
        DB::statement("ALTER TABLE producto_cobertura CHANGE `ProductoId` `Producto` BIGINT(20) UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE producto_datos_tecnicos CHANGE `ProductoId` `Producto` BIGINT(20) UNSIGNED NOT NULL");

        // Revertir nombres de tablas
        Schema::rename('producto_cobertura', 'cobertura');
        Schema::rename('producto_cobertura_tarificacion', 'cobertura_tarificacion');
        Schema::rename('producto_datos_tecnicos', 'datos_tecnicos');
    }
};
