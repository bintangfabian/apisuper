<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $student = User::create([
        //     "name" => "Bintang Fabian",
        //     "email" => "bintangalfin33@gmail.com",
        //     "email_verified_at" => now()->timezone('Asia/Jakarta'),
        //     "password" => Hash::make("bintang"),
        // ]);
        // $student->assignRole('student');
        // $picName = md5("tim sukses") . '.' . 'png';
        // $avatar = Avatar::create("Tim Sukses")->getImageObject()->encode('png');
        // Storage::put('public/images/avatars/' . $picName, (string) $avatar);
        // $avtar = [];
        // $avtar['user_id'] = 1;
        // $avtar['image'] = $picName;
        // Avtar::create($avtar);
    }
}
