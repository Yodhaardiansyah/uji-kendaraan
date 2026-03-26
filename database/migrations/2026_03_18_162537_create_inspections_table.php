<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

            // Relasi Utama
            $table->foreignId('rfid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins');

            // ================= A. FOTO KENDARAAN =================
            $table->string('foto_depan')->nullable();
            $table->string('foto_belakang')->nullable();
            $table->string('foto_kanan')->nullable();
            $table->string('foto_kiri')->nullable();

            // ================= B. PEMERIKSAAN VISUAL =================
            $table->boolean('rangka')->default(0);
            $table->boolean('mesin')->default(0); // Tipe motor penggerak
            $table->boolean('tangki')->default(0);
            $table->boolean('pembuangan')->default(0);
            $table->boolean('ban')->default(0);
            $table->boolean('suspensi')->default(0);
            $table->boolean('rem_utama')->default(0);
            $table->boolean('lampu')->default(0);
            $table->boolean('dashboard')->default(0);
            $table->boolean('spion')->default(0);
            $table->boolean('spakbor')->default(0);
            $table->boolean('bumper')->default(0);
            $table->boolean('perlengkapan')->default(0);
            $table->boolean('teknis')->default(0); // Rancangan teknis
            $table->boolean('darurat')->default(0); // Fasilitas tanggap darurat
            $table->boolean('badan')->default(0); // Kondisi badan/kaca/engsel
            $table->boolean('converter')->default(0); // Converter kit

            // ================= C. PEMERIKSAAN MANUAL =================
            $table->boolean('penerus_daya')->default(0);
            $table->boolean('kemudi')->default(0); // Sudut bebas kemudi
            $table->boolean('rem_parkir')->default(0);
            $table->boolean('lampu_manual')->default(0);
            $table->boolean('wiper')->default(0);
            $table->boolean('kaca')->default(0); // Tingkat kegelapan
            $table->boolean('klakson')->default(0);
            $table->boolean('sabuk')->default(0);
            $table->boolean('ukuran')->default(0);
            $table->boolean('kursi')->default(0); // Akses darurat / kursi

            // ================= D. PEMERIKSAAN ALAT UJI =================
            // 1. Emisi
            $table->float('emisi_solar')->nullable(); // %
            $table->float('emisi_co')->nullable(); // %
            $table->integer('emisi_hc')->nullable(); // ppm

            // 2. Rem Utama
            $table->float('rem_utama_total')->nullable(); // %
            $table->float('rem_utama_selisih_1')->nullable(); // %
            $table->float('rem_utama_selisih_2')->nullable(); // %
            $table->float('rem_utama_selisih_3')->nullable(); // %
            $table->float('rem_utama_selisih_4')->nullable(); // %

            // 3. Rem Parkir
            $table->float('rem_parkir_tangan')->nullable(); // %
            $table->float('rem_parkir_kaki')->nullable(); // %

            // 4. Kincup Roda
            $table->float('kincup_roda_depan')->nullable(); // mm/mnt (float agar bisa desimal)

            // ================= E. TINGKAT KEBISINGAN =================
            $table->integer('kebisingan')->nullable(); // db(A)

            // ================= F. LAMPU UTAMA =================
            $table->integer('lampu_kanan')->nullable(); // cd
            $table->integer('lampu_kiri')->nullable(); // cd
            $table->float('deviasi_kanan')->nullable();
            $table->float('deviasi_kiri')->nullable();

            // ================= G. PETUNJUK KECEPATAN =================
            $table->float('speed_deviasi')->nullable(); // km/jam

            // ================= H. KEDALAMAN ALUR BAN =================
            $table->float('alur_ban')->nullable(); // mm

            // ================= I. KETERANGAN HASIL UJI =================
            $table->enum('hasil', [
                'Lolos Uji Berkala',
                'Tidak Lolos Uji Berkala',
                'Menunggu Hasil Uji'
            ])->default('Menunggu Hasil Uji');
            $table->date('tgl_uji');
            $table->date('tgl_berlaku')->nullable();

            // ================= J. UNIT PELAKSANA =================
            $table->string('nama_unit')->nullable();

            // ================= K. PETUGAS PENGUJI =================
            $table->string('nama_petugas');
            $table->string('nrp');
            $table->string('pangkat_petugas')->nullable();

            // ================= L. KEPALA DINAS =================
            $table->string('kepala_dinas_nama')->nullable();
            $table->string('kepala_dinas_nip')->nullable();
            $table->string('kepala_dinas_pangkat')->nullable();

            // ================= M. DIREKTUR =================
            $table->string('direktur_nama')->nullable();
            $table->string('direktur_nip')->nullable();
            $table->string('direktur_pangkat')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};