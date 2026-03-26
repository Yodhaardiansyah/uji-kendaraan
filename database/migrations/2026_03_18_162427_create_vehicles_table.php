<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('no_uji');
        $table->string('no_srut')->nullable();
        $table->date('tgl_srut')->nullable();
        $table->string('no_kendaraan');
        $table->string('no_mesin');
        $table->string('no_rangka');

        $table->string('merk');
        $table->string('tipe');
        $table->enum('jenis', [
            'Sepeda Motor',
            'Mobil Penumpang',
            'Mobil Bus',
            'Mobil Barang',
            'Kendaraan Khusus'
        ]);

        $table->year('tahun');
        $table->enum('bahan_bakar', ['Bensin', 'Solar', 'Listrik']);

        $table->integer('cc')->nullable();
        $table->integer('daya_hp')->nullable();

        $table->integer('jbb')->nullable();
        $table->integer('jbkb')->nullable();
        $table->integer('jbi')->nullable();
        $table->integer('jbki')->nullable();
        $table->integer('mst')->nullable();
        $table->integer('berat_kosong')->nullable();

        $table->float('konfigurasi_sumbu')->nullable();

        // ban (dipisah)
        $table->string('ban_depan')->nullable();
        $table->string('ban_belakang')->nullable();
        $table->string('ban_ring')->nullable();

        // dimensi
        $table->integer('panjang')->nullable();
        $table->integer('lebar')->nullable();
        $table->integer('tinggi')->nullable();

        $table->integer('panjang_bak')->nullable();
        $table->integer('lebar_bak')->nullable();
        $table->integer('tinggi_bak')->nullable();

        $table->integer('jalur_depan')->nullable();
        $table->integer('jalur_belakang')->nullable();

        $table->integer('sumbu_1_2')->nullable();
        $table->integer('sumbu_2_3')->nullable();
        $table->integer('sumbu_3_4')->nullable();

        $table->integer('daya_orang')->nullable();
        $table->integer('daya_barang')->nullable();

        $table->enum('kelas_jalan', ['Kelas I', 'Kelas II', 'Kelas III', 'Kelas Khusus'])->nullable();

        $table->string('wilayah')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
