<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert([
            [
                'name' => 'X RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'X TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XI RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XI TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XII RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XII TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
