<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_beneficiarios', 'Dui')) {
                $table->string('Dui', 10)->nullable()->after('Nombre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro_beneficiarios', 'Dui')) {
                $table->dropColumn('Dui');
            }
        });
    }
};
