<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EssayAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('essay_answers')->insert([
            [
                'question_id' => 4,
                'content' => 'II A',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
