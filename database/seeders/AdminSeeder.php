<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Akun Statis Utama (Agar kamu gampang login untuk testing)
        Admin::create([
            'nama' => 'Super Admin Pusat',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'nrp' => '198001012005011001',
            'dishub_id' => 1, // Ditaruh di Dishub ID 1
        ]);

        // 2. Generate 19 Admin Cabang secara acak
        for ($i = 0; $i < 19; $i++) {
            Admin::create([
                // Membuat nama admin terlihat seperti "Admin Cabang Bandung", dll
                'nama' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'), // Password disamakan semua
                'role' => 'admin',
                // Membuat 18 digit angka acak yang menyerupai format NIP/NRP Pegawai
                'nrp' => $faker->numerify('199#########100#'), 
                // Mengambil ID Dishub secara acak dari angka 1 sampai 20
                'dishub_id' => rand(1, 20), 
            ]);
        }
    }
}