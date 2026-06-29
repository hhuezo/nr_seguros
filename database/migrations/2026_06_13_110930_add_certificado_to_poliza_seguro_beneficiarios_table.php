<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro_beneficiarios', 'PolizaSeguroCertificadoId')) {
                $table->unsignedBigInteger('PolizaSeguroCertificadoId')->nullable()->after('PolizaSeguroId');
                $table->foreign('PolizaSeguroCertificadoId', 'fk_beneficiario_certificado')
                    ->references('Id')
                    ->on('poliza_seguro_certificados')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poliza_seguro_beneficiarios', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro_beneficiarios', 'PolizaSeguroCertificadoId')) {
                $table->dropForeign('fk_beneficiario_certificado');
                $table->dropColumn('PolizaSeguroCertificadoId');
            }
        });
    }
};
