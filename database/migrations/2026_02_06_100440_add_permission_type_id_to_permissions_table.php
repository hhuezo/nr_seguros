<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Añadimos la columna después del ID
            $table->foreignId('permission_type_id')
                  ->nullable() // Permite nulos para no romper datos existentes
                  ->after('id')
                  ->constrained('permission_types')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_type_id']);
            $table->dropColumn('permission_type_id');
        });
    }
};
