<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            $table->integer('DiasProrrata')->nullable()->after('Tasa');
            $table->decimal('PrimaAnual', 14, 2)->nullable()->after('DiasProrrata');
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificado_coberturas', function (Blueprint $table) {
            $table->dropColumn(['DiasProrrata', 'PrimaAnual']);
        });
    }
};
