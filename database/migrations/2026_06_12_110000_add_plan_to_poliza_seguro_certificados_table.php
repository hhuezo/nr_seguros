<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_certificados', 'Plan')) {
                $table->unsignedBigInteger('Plan')->nullable()->after('PolizaSeguroId');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_certificados', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro_certificados', 'Plan')) {
                $table->dropColumn('Plan');
            }
        });
    }
};
