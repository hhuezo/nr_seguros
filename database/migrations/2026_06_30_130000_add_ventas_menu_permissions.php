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
            'ventas menu',
            'ventas-ofertas read',
            'ventas-ofertas create',
            'ventas-ofertas edit',
            'ventas-ofertas delete',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['permission_type_id' => 9]
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
                'ventas menu',
                'ventas-ofertas read',
                'ventas-ofertas create',
                'ventas-ofertas edit',
                'ventas-ofertas delete',
            ])
            ->delete();
    }
};
