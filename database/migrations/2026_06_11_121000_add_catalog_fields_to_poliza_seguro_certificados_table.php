<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'EstadoCertificado')) {
                $table->unsignedBigInteger('EstadoCertificado')->nullable()->after('Estado');
            }

            if (!Schema::hasColumn('poliza_seguro_certificados', 'MotivoCancelacion')) {
                $table->unsignedBigInteger('MotivoCancelacion')->nullable()->after('EstadoCertificado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            foreach (['MotivoCancelacion', 'EstadoCertificado'] as $column) {
                if (Schema::hasColumn('poliza_seguro_certificados', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
