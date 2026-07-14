<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forma_pago_polizas', function (Blueprint $table) {
            if (!Schema::hasColumn('forma_pago_polizas', 'Orden')) {
                $table->integer('Orden')->nullable()->after('Nombre');
            }
        });

        if (Schema::hasColumn('forma_pago_polizas', 'Orden')) {
            DB::table('forma_pago_polizas')
                ->whereNull('Orden')
                ->orderBy('Id')
                ->get(['Id'])
                ->each(function ($formaPago) {
                    DB::table('forma_pago_polizas')
                        ->where('Id', $formaPago->Id)
                        ->update(['Orden' => $formaPago->Id]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('forma_pago_polizas', function (Blueprint $table) {
            if (Schema::hasColumn('forma_pago_polizas', 'Orden')) {
                $table->dropColumn('Orden');
            }
        });
    }
};
