<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelas')->insert([
            [
                'class_name' => 'X RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'X TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'XI RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'XI TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'XII RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'XII TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
