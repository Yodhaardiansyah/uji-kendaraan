<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Inisialisasi Faker dengan lokalisasi bahasa Indonesia
        $faker = Faker::create('id_ID');

        // 1. Akun statis (Untuk testing login kamu)
        User::create([
            'nama' => 'Budi Pemilik',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'nomor_identitas' => '3201234567890001',
            'alamat' => 'Jl. Tebet Raya No. 1, Jakarta Selatan',
            'role' => 'user',
        ]);

        // 2. Generate 19 akun acak menggunakan Faker
        for ($i = 0; $i < 19; $i++) {
            User::create([
                'nama' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'), // Disamakan semua agar mudah dites
                'nomor_identitas' => $faker->nik(), // Otomatis membuat 16 digit angka KTP
                'alamat' => $faker->address(),
                'role' => 'user',
            ]);
        }
    }
}