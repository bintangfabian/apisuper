<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            NewsSeeder::class,
            ChapterSeeder::class,
            LearningMaterialSeeder::class,
            EssayAnswerSeeder::class,
            OptionSeeder::class,
            QuestionSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            SubjectSeeder::class,
            GradeSeeder::class,
        ]);
    }
}
