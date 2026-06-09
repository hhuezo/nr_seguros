<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('producto_certificado_campos', 'ValidacionCampo')) {
            Schema::table('producto_certificado_campos', function (Blueprint $table) {
                $table->string('ValidacionCampo', 50)
                    ->default('ninguna')
                    ->after('TipoCampo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('producto_certificado_campos', 'ValidacionCampo')) {
            Schema::table('producto_certificado_campos', function (Blueprint $table) {
                $table->dropColumn('ValidacionCampo');
            });
        }
    }
};
