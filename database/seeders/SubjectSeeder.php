<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SubjectSeeder extends Seeder
{
    protected $name = [
        'Matematika',
        'Pemrograman',
        'Bahasa Inggris',
        'Fisika',
        'Kimia',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < count($this->name); $i++) {
            DB::table('subjects')->insert(
                [
                    'name' => $this->name[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
