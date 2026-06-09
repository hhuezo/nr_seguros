<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto_certificado_campos', function (Blueprint $table) {
            $table->tinyInteger('MostrarEnReporte')->default(0)->after('Requerido');
        });
    }

    public function down(): void
    {
        Schema::table('producto_certificado_campos', function (Blueprint $table) {
            $table->dropColumn('MostrarEnReporte');
        });
    }
};

