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

        DB::table('permissions')->updateOrInsert(
            ['name' => 'catalogos-comerciales menu', 'guard_name' => 'web'],
            ['permission_type_id' => 4]
        );

        $adminRoleId = DB::table('roles')->where('id', 1)->value('id');
        $permissionId = DB::table('permissions')
            ->where('name', 'catalogos-comerciales menu')
            ->value('id');

        if ($adminRoleId && $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        }
    }

    public function down()
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::table('permissions')
            ->where('name', 'catalogos-comerciales menu')
            ->delete();
    }
};
