<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('permission_types') || !Schema::hasTable('permissions')) {
            return;
        }

        $permissionGroups = [
            'Catalogos comerciales' => [
                'catalogos-comerciales menu',
                'ventas-campo-comparativo read',
                'ventas-campo-comparativo create',
                'ventas-campo-comparativo edit',
                'ventas-campo-comparativo delete',
                'ventas-plan-comercial read',
                'ventas-plan-comercial create',
                'ventas-plan-comercial edit',
                'ventas-plan-comercial delete',
            ],
            'Catalogos polizas no declarativas' => [
                'catalogos-polizas-no-declarativas menu',
                'motivo-cancelacion read',
                'motivo-cancelacion create',
                'motivo-cancelacion edit',
                'motivo-cancelacion delete',
                'estado-certificado read',
                'estado-certificado create',
                'estado-certificado edit',
                'estado-certificado delete',
                'forma-pago-poliza read',
                'forma-pago-poliza create',
                'forma-pago-poliza edit',
                'forma-pago-poliza delete',
                'origen-poliza read',
                'origen-poliza create',
                'origen-poliza edit',
                'origen-poliza delete',
                'tipo-deducible read',
                'tipo-deducible create',
                'tipo-deducible edit',
                'tipo-deducible delete',
                'parentesco-beneficiario read',
                'parentesco-beneficiario create',
                'parentesco-beneficiario edit',
                'parentesco-beneficiario delete',
                'cesionario read',
                'cesionario create',
                'cesionario edit',
                'cesionario delete',
            ],
            'Configuracion de poliza' => [
                'configuracion-poliza menu',
                'agrupador-ramo read',
                'agrupador-ramo create',
                'agrupador-ramo edit',
                'agrupador-ramo delete',
                'tipo-poliza read',
                'tipo-poliza create',
                'tipo-poliza edit',
                'tipo-poliza delete',
                'ramo read',
                'ramo create',
                'ramo edit',
                'ramo delete',
                'producto read',
                'producto create',
                'producto edit',
                'producto delete',
                'plan read',
                'plan create',
                'plan edit',
                'plan delete',
            ],
            'Ventas' => [
                'ventas menu',
                'ventas-ofertas read',
                'ventas-ofertas create',
                'ventas-ofertas edit',
                'ventas-ofertas delete',
            ],
        ];

        foreach ($permissionGroups as $groupName => $permissions) {
            DB::table('permission_types')->updateOrInsert(
                ['name' => $groupName],
                ['active' => 1]
            );

            $permissionTypeId = DB::table('permission_types')
                ->where('name', $groupName)
                ->value('id');

            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['permission_type_id' => $permissionTypeId]
                );
            }
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
};
