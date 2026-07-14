<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $permissions = [
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

    public function down()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::table('permissions')
            ->whereIn('name', [
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
            ])
            ->delete();
    }
};
