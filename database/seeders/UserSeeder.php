<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student1 = User::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => 'admin',
        ]);
        $student1->assignRole("Admin");
        $avatar1 = Avatar::create($student1->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $student1->id . '/avatar.png', (string) $avatar1);
        $student1->image()->create(['path' => "avatars/$student1->id/avatar.png", 'thumbnail' => true]);


        $student2 = User::create([
            "name" => "kepala sekolah",
            "email" => "kepalasekolah@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => 'kepalasekolah',
        ]);
        $student2->assignRole("Kepala Sekolah");
        $avatar2 = Avatar::create($student2->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $student2->id . '/avatar.png', (string) $avatar2);
        $student2->image()->create(['path' => "avatars/$student2->id/avatar.png", 'thumbnail' => true]);

        $student3 = User::create([
            "name" => "guru",
            "email" => "guru@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => "guru",
        ]);
        $student3->assignRole("Guru");
        $avatar3 = Avatar::create($student3->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $student3->id . '/avatar.png', (string) $avatar3);
        $student3->image()->create(['path' => "avatars/$student3->id/avatar.png", 'thumbnail' => true]);

        $student4 = User::create([
            "name" => "murid",
            "email" => "murid@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => "murid",
        ]);
        $student4->student()->create(['user_id' => $student4->id, 'grade_id' => 1]);
        $student4->assignRole("Siswa");
        $avatar4 = Avatar::create($student4->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $student4->id . '/avatar.png', (string) $avatar4);
        $student4->image()->create(['path' => "avatars/$student4->id/avatar.png", 'thumbnail' => true]);

        $user = User::create([
            "name" => "murid2",
            "email" => "murid2@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => "murid2",
        ]);
        $user->student()->create(['user_id' => $user->id, 'grade_id' => 2]);
        $user->assignRole("Siswa");
        $avatar4 = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $user->id . '/avatar.png', (string) $avatar4);
        $user->image()->create(['path' => "avatars/$user->id/avatar.png", 'thumbnail' => true]);

        $user2 = User::create([
            "name" => "murid3",
            "email" => "murid3@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => "murid3",
        ]);
        $user2->student()->create(['user_id' => $user2->id, 'grade_id' => 2]);
        $user2->assignRole("Siswa");
        $avatar4 = Avatar::create($user2->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $user2->id . '/avatar.png', (string) $avatar4);
        $user2->image()->create(['path' => "avatars/$user2->id/avatar.png", 'thumbnail' => true]);

        $student5 = User::create([
            "name" => "wali santri",
            "email" => "walisantri@gmail.com",
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            "password" => "walisantri",
        ]);
        $student5->assignRole("Wali Siswa");
        $avatar5 = Avatar::create($student5->name)->getImageObject()->encode('png');
        Storage::put('public/images/avatars/' . $student5->id . '/avatar.png', (string) $avatar5);
        $student5->image()->create(['path' => "avatars/$student5->id/avatar.png", 'thumbnail' => true]);

        // $picName = md5("tim sukses") . '.' . 'png';
        // $avatar = Avatar::create("Tim Sukses")->getImageObject()->encode('png');
        // Storage::put('public/images/avatars/' . $picName, (string) $avatar);
        // $avtar = [];
        // $avtar['user_id'] = 1;
        // $avtar['image'] = $picName;
        // Avtar::create($avtar);
    }
}
