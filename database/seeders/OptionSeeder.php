<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->insert([
            [
                'question_id' => '1',
                'name' => 'a',
                'content' => 'proton',
                'is_true' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '1',
                'name' => 'b',
                'content' => 'elektron',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '1',
                'name' => 'c',
                'content' => 'neutron',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '1',
                'name' => 'd',
                'content' => 'Alkali',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '2',
                'name' => 'a',
                'content' => 'proton',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '2',
                'name' => 'b',
                'content' => 'elektron',
                'is_true' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '2',
                'name' => 'c',
                'content' => 'neutron',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '2',
                'name' => 'd',
                'content' => 'Alkali',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '3',
                'name' => 'a',
                'content' => 'proton',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '3',
                'name' => 'b',
                'content' => 'elektron',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '3',
                'name' => 'c',
                'content' => 'neutron',
                'is_true' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => '3',
                'name' => 'd',
                'content' => 'Alkali',
                'is_true' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
