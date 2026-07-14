<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas_campo_comparativo', function (Blueprint $table) {
            foreach (['TipoDato', 'FormatoVisual', 'Grupo', 'Descripcion', 'MostrarEnComparativo', 'MostrarEnPdf'] as $column) {
                if (Schema::hasColumn('ventas_campo_comparativo', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('ventas_campo_comparativo', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas_campo_comparativo', 'TipoDato')) {
                $table->string('TipoDato', 30)->default('texto')->after('NombreInterno');
            }
            if (!Schema::hasColumn('ventas_campo_comparativo', 'FormatoVisual')) {
                $table->string('FormatoVisual', 50)->nullable()->after('TipoDato');
            }
            if (!Schema::hasColumn('ventas_campo_comparativo', 'Grupo')) {
                $table->string('Grupo', 150)->nullable()->after('FormatoVisual');
            }
            if (!Schema::hasColumn('ventas_campo_comparativo', 'Descripcion')) {
                $table->text('Descripcion')->nullable()->after('Grupo');
            }
            if (!Schema::hasColumn('ventas_campo_comparativo', 'MostrarEnComparativo')) {
                $table->tinyInteger('MostrarEnComparativo')->default(1)->after('Orden');
            }
            if (!Schema::hasColumn('ventas_campo_comparativo', 'MostrarEnPdf')) {
                $table->tinyInteger('MostrarEnPdf')->default(1)->after('MostrarEnComparativo');
            }
        });
    }
};
