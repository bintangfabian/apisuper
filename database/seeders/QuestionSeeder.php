<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'content' => 'Bagian Atom yang positif adalah',
                'subject_id' => '5',
                'grade_id' => '5',
                'user_id' => '3',
                'type' => 'Multiple Choice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Bagian Atom yang negatif adalah',
                'subject_id' => '5',
                'grade_id' => '5',
                'user_id' => '3',
                'type' => 'Multiple Choice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Bagian Atom yang netral adalah',
                'subject_id' => '5',
                'grade_id' => '5',
                'user_id' => '3',
                'type' => 'Multiple Choice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Alkali tanah merupakan golongan',
                'subject_id' => '5',
                'grade_id' => '5',
                'user_id' => '3',
                'type' => 'Essay',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
