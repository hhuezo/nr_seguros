<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro', 'DatosRamo')) {
                $table->longText('DatosRamo')->nullable()->after('Observacion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro', 'DatosRamo')) {
                $table->dropColumn('DatosRamo');
            }
        });
    }
};
