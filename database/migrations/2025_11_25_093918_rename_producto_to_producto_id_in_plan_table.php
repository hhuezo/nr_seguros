<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Para MariaDB: usar CHANGE en vez de renameColumn
        DB::statement("ALTER TABLE plan CHANGE `Producto` `ProductoId` BIGINT(20) UNSIGNED NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE plan CHANGE `ProductoId` `Producto` BIGINT(20) UNSIGNED NOT NULL");
    }
};
