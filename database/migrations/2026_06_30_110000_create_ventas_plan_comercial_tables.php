<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ventas_plan_comercial')) {
            Schema::create('ventas_plan_comercial', function (Blueprint $table) {
                $table->id('Id');
                $table->unsignedBigInteger('Aseguradora')->index();
                $table->unsignedBigInteger('NecesidadProteccion')->index();
                $table->unsignedBigInteger('Producto')->index();
                $table->unsignedBigInteger('Plan')->index();
                $table->string('NombreComercial', 200);
                $table->tinyInteger('Activo')->default(1);
            });
        }

        if (!Schema::hasTable('ventas_plan_comercial_valor')) {
            Schema::create('ventas_plan_comercial_valor', function (Blueprint $table) {
                $table->id('Id');
                $table->unsignedBigInteger('PlanComercial')->index();
                $table->unsignedBigInteger('CampoComparativo')->index();
                $table->text('ValorTexto')->nullable();
                $table->tinyInteger('Activo')->default(1);
            });
        }

        if (Schema::hasTable('permissions')) {
            $permissions = [
                'ventas-plan-comercial read',
                'ventas-plan-comercial create',
                'ventas-plan-comercial edit',
                'ventas-plan-comercial delete',
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
        Schema::dropIfExists('ventas_plan_comercial_valor');
        Schema::dropIfExists('ventas_plan_comercial');

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', [
                    'ventas-plan-comercial read',
                    'ventas-plan-comercial create',
                    'ventas-plan-comercial edit',
                    'ventas-plan-comercial delete',
                ])
                ->delete();
        }
    }
};
