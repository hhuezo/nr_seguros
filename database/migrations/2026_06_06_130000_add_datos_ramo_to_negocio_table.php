<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('negocio', function (Blueprint $table) {
            $table->longText('DatosRamo')->nullable()->after('Observacion');
        });
    }

    public function down(): void
    {
        Schema::table('negocio', function (Blueprint $table) {
            $table->dropColumn('DatosRamo');
        });
    }
};
