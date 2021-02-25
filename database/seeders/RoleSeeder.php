<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
        Role::create([
            'name' => '1',
            'guard_name' => 'web'
        ]);

        //kepala sekolah
        Role::create([
            'name' => '2',
            'guard_name' => 'web'
        ]);

        // guru
        Role::create([
            'name' => '3',
            'guard_name' => 'web'
        ]);

        // murid
        Role::create([
            'name' => '4',
            'guard_name' => 'web'
        ]);

        // wali santri
        Role::create([
            'name' => '5',
            'guard_name' => 'web'
        ]);
    }
}
