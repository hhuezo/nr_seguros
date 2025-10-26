<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poliza_desempleo_cartera', function (Blueprint $table) {
            // Agregar nuevas columnas
            $table->decimal('TotalCredito', 28, 14)->nullable()->after('DesempleoTipoCartera');
            $table->decimal('Tasa', 28, 14)->nullable()->after('TotalCredito');
        });
    }

    public function down(): void
    {
        Schema::table('poliza_desempleo_cartera', function (Blueprint $table) {
            // Eliminar columnas en caso de rollback
            $table->dropColumn(['TotalCredito', 'Tasa']);
        });
    }
};
