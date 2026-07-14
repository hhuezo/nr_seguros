<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ventas_campo_comparativo')) {
            Schema::create('ventas_campo_comparativo', function (Blueprint $table) {
                $table->id('Id');
                $table->unsignedBigInteger('NecesidadProteccion')->index();
                $table->string('Etiqueta', 150);
                $table->string('NombreInterno', 150);
                $table->integer('Orden')->default(1);
                $table->tinyInteger('Activo')->default(1);
            });
        }

        if (Schema::hasTable('permissions')) {
            $permissions = [
                'ventas-campo-comparativo read',
                'ventas-campo-comparativo create',
                'ventas-campo-comparativo edit',
                'ventas-campo-comparativo delete',
            ];

            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['permission_type_id' => 4]
                );
            }

            $adminRoleId = DB::table('roles')->where('id', 1)->value('id');
            if ($adminRoleId) {
                $permissionIds = DB::table('permissions')
                    ->whereIn('name', $permissions)
                    ->pluck('id');

                foreach ($permissionIds as $permissionId) {
                    DB::table('role_has_permissions')->updateOrInsert([
                        'permission_id' => $permissionId,
                        'role_id' => $adminRoleId,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('ventas_campo_comparativo');

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', [
                    'ventas-campo-comparativo read',
                    'ventas-campo-comparativo create',
                    'ventas-campo-comparativo edit',
                    'ventas-campo-comparativo delete',
                ])
                ->delete();
        }
    }
};
