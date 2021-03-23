<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('learning_materials')->insert([
            [
                "title" => "Kesamaan Formasi",
                "content" => "<h2>Lorem</h2>",
                "subject_id" => 1,
                "chapter_id" => 1,
                'user_id' => 3,
                "grade_id" => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "Analisis Vektor",
                "content" => "<h2>Lorem</h2>",
                "subject_id" => 2,
                "chapter_id" => 4,
                'user_id' => 3,
                "grade_id" => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "V-ing",
                "content" => "<h2>Lorem</h2>",
                "subject_id" => 3,
                "chapter_id" => 3,
                'user_id' => 3,
                "grade_id" => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "Keelektronegatifan",
                "content" => "<h2>Lorem</h2>",
                "subject_id" => 5,
                "chapter_id" => 5,
                'user_id' => 3,
                "grade_id" => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
