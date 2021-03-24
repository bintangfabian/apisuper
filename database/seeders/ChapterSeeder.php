<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterSeeder extends Seeder
{
    protected $name = [
        'Aljabar',
        'Vektor',
        'Gerund',
        'Termodinamika',
        'Ikatan Kimia',
    ];

    protected $subject_id = [
        1,
        4,
        3,
        4,
        5,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < count($this->name); $i++) {
            DB::table('chapters')->insert(
                [
                    'name' => $this->name[$i],
                    'user_id' => 3,
                    'subject_id' => $this->subject_id[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
