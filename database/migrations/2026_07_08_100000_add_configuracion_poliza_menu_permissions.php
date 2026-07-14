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
                'configuracion-poliza menu',
                'agrupador-ramo read',
                'agrupador-ramo create',
                'agrupador-ramo edit',
                'agrupador-ramo delete',
            ])
            ->delete();
    }
};
