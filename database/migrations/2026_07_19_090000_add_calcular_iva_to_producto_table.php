<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            if (!Schema::hasColumn('producto', 'CalcularIva')) {
                $table->tinyInteger('CalcularIva')->default(0)->after('PorcentajeComisionNoDeclarativa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            if (Schema::hasColumn('producto', 'CalcularIva')) {
                $table->dropColumn('CalcularIva');
            }
        });
    }
};
