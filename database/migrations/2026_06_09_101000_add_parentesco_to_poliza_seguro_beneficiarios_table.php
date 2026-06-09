<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_beneficiarios', 'Parentesco')) {
                $table->unsignedBigInteger('Parentesco')->nullable()->after('Nombre');
                $table->foreign('Parentesco', 'fk_poliza_seguro_beneficiario_parentesco')
                    ->references('Id')
                    ->on('parentesco');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro_beneficiarios', 'Parentesco')) {
                $table->dropForeign('fk_poliza_seguro_beneficiario_parentesco');
                $table->dropColumn('Parentesco');
            }
        });
    }
};
