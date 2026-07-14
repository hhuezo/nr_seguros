<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto_certificado_campos', function (Blueprint $table) {
            if (!Schema::hasColumn('producto_certificado_campos', 'OrigenOpciones')) {
                $table->string('OrigenOpciones', 30)->default('manual')->after('OpcionesJson');
            }

            if (!Schema::hasColumn('producto_certificado_campos', 'CatalogoOrigen')) {
                $table->string('CatalogoOrigen', 80)->nullable()->after('OrigenOpciones');
            }
        });
    }

    public function down(): void
    {
        Schema::table('producto_certificado_campos', function (Blueprint $table) {
            if (Schema::hasColumn('producto_certificado_campos', 'CatalogoOrigen')) {
                $table->dropColumn('CatalogoOrigen');
            }

            if (Schema::hasColumn('producto_certificado_campos', 'OrigenOpciones')) {
                $table->dropColumn('OrigenOpciones');
            }
        });
    }
};
