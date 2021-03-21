<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news')->insert([
            [
                "user_id" => 1,
                "title" => "Penalaran Induktif",
                "content" => "<p>Penalaran bla bla</p>",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "user_id" => 1,
                "title" => "Penalaran Deduktif",
                "content" => "<p>Penalaran bla bla</p>",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "user_id" => 1,
                "title" => "SOLID PRINCIPLES",
                "content" => "<p>uncle bob is author of ....</p>",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
