<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Dishub;
use Faker\Factory as Faker;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan bahasa Indonesia untuk Faker
        $faker = Faker::create('id_ID');

        // Mengambil data User dan Wilayah yang sudah ada
        $userIds = User::pluck('id')->toArray();
        $wilayahs = Dishub::pluck('nama')->toArray();

        // Validasi agar tidak error jika tabel users/dishubs masih kosong
        if (empty($userIds) || empty($wilayahs)) {
            $this->command->error('❌ Gagal: Pastikan tabel users dan dishubs minimal punya 1 data terlebih dahulu!');
            return;
        }

        // Looping untuk membuat 20 data percobaan
        for ($i = 1; $i <= 20; $i++) {
            Vehicle::create([
                'user_id' => $faker->randomElement($userIds),
                'no_uji' => $faker->unique()->numerify('KIR-########'),
                'no_srut' => $faker->optional()->numerify('SRUT-########'),
                'tgl_srut' => $faker->optional()->date(),
                'no_kendaraan' => $faker->unique()->regexify('[A-Z]{1,2} [1-9]{1}[0-9]{1,3} [A-Z]{1,3}'),
                'no_mesin' => $faker->unique()->bothify('MSN-????####'),
                'no_rangka' => $faker->unique()->bothify('RKG-????####'),
                'merk' => $faker->randomElement(['Toyota', 'Mitsubishi', 'Hino', 'Isuzu', 'Suzuki', 'Daihatsu', 'Honda']),
                'tipe' => $faker->word(),
                'jenis' => $faker->randomElement(['Sepeda Motor', 'Mobil Penumpang', 'Mobil Bus', 'Mobil Barang', 'Kendaraan Khusus']),
                'tahun' => $faker->numberBetween(2010, 2026),
                'bahan_bakar' => $faker->randomElement(['Bensin', 'Solar', 'Listrik']),
                'cc' => $faker->randomElement([1500, 2000, 2500, 4000, 6000]),
                'daya_hp' => $faker->numberBetween(100, 350),
                'jbb' => $faker->numberBetween(2000, 8000),
                'jbkb' => $faker->numberBetween(2000, 8000),
                'jbi' => $faker->numberBetween(2000, 8000),
                'jbki' => $faker->numberBetween(2000, 8000),
                'mst' => $faker->numberBetween(1500, 5000),
                'berat_kosong' => $faker->numberBetween(1000, 4000),
                'konfigurasi_sumbu' => $faker->randomElement(['1.1', '1.2', '1.22', '1.1.2']),
                'ban_depan' => $faker->randomElement(['7.50', '8.25', '10.00']),
                'ban_belakang' => $faker->randomElement(['7.50', '8.25', '10.00']),
                'ban_ring' => $faker->randomElement(['14', '15', '16', '20']),
                'panjang' => $faker->numberBetween(4000, 12000),
                'lebar' => $faker->numberBetween(1700, 2500),
                'tinggi' => $faker->numberBetween(1500, 3500),
                'panjang_bak' => $faker->optional()->numberBetween(2000, 8000),
                'lebar_bak' => $faker->optional()->numberBetween(1500, 2500),
                'tinggi_bak' => $faker->optional()->numberBetween(500, 2000),
                'jalur_depan' => $faker->numberBetween(1300, 2000),
                'jalur_belakang' => $faker->numberBetween(1300, 2000),
                'sumbu_1_2' => $faker->numberBetween(2500, 6000),
                'sumbu_2_3' => $faker->optional()->numberBetween(1000, 1500),
                'sumbu_3_4' => $faker->optional()->numberBetween(1000, 1500),
                'daya_orang' => $faker->numberBetween(2, 60),
                'daya_barang' => $faker->numberBetween(1000, 15000),
                'kelas_jalan' => $faker->randomElement(['Kelas I', 'Kelas II', 'Kelas III', 'Kelas Khusus']),
                'wilayah' => $faker->randomElement($wilayahs)
            ]);
        }

        $this->command->info('✅ 20 Data Kendaraan percobaan berhasil ditambahkan!');
    }
}