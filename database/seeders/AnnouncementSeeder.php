<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('announcements')->insert(
            [
                'user_id' => 1,
                'title' => 'Hello gais',
                'description' => 'ini deskripsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
