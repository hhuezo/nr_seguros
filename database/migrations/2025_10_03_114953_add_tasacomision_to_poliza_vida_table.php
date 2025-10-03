<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_vida', function (Blueprint $table) {
            $table->decimal('TasaComision', 12, 6)->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('poliza_vida', function (Blueprint $table) {
            $table->dropColumn('TasaComision');
        });
    }
};
