<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $arrayOfPermissionNames = [
            'login',
            'register',
            'forgot password',
            'edit profile',
            'recap user',
            'edit permission',
            'crud student attandance',
            'crud chapter',
            'crud learning materials',
            'crud exam',
            'crud score',
            'crud attitude assessment',
            'recap student attandance',
            'exam',
            'crud announcement',
            'view announcement',
            'crud news',
            'view news',
        ];
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
        return ['name' => $permission, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()];
        });

        Permission::insert($permissions->toArray());

        $permissionsByRole = [
            'Admin' => [
                'login',
                'register',
                'forgot password',
                'edit profile',
                'recap user',
                'edit permission',
                'crud announcement',
                'view announcement',
                'crud news',
                'view news',
            ],
            'Kepala Sekolah' => [
                'login',
                'forgot password',
                'edit profile',
                'recap user',
                'crud announcement',
                'crud news',
            ],
            'Guru' => [
                'login',
                'forgot password',
                'edit profile',
                'crud student attandance',
                'crud chapter',
                'crud learning materials',
                'crud exam',
                'crud score',
                'crud attitude assessment',
                'recap student attandance',
                'crud announcement',
                'view announcement',
                'view news',
            ],
            'Siswa' => [
                'login',
                'forgot password',
                'edit profile',
                'recap student attandance',
                'exam',
                'view announcement',
                'view news',
            ],
            'Wali Siswa' => [
                'login',
                'forgot password',
                'edit profile',
                'recap student attandance',
                'view announcement',
                'view news',
            ],
        ];
        // DB::table('permissions')->insertGetId(['name' => $name])
        $insertPermissions = fn ($role) => collect($permissionsByRole[$role])
            ->map(fn ($name) => (Permission::findByName($name)->id))
            ->toArray();

        $permissionIdsByRole = [
            'Admin' => $insertPermissions('Admin'),
            'Kepala Sekolah' => $insertPermissions('Kepala Sekolah'),
            'Guru' => $insertPermissions('Guru'),
            'Siswa' => $insertPermissions('Siswa'),
            'Wali Siswa' => $insertPermissions('Wali Siswa'),
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::create(['name' => $role]);
            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }
    }
}
