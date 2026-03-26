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
        Schema::table('vehicles', function (Blueprint $table) {
            // 1. Mengubah tipe data konfigurasi_sumbu dari float menjadi string
            $table->string('konfigurasi_sumbu')->nullable()->change();

            // 2. Menambahkan unique constraint agar tidak ada data ganda
            $table->unique('no_uji');
            $table->unique('no_kendaraan');
            $table->unique('no_mesin');
            $table->unique('no_rangka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // 1. Mengembalikan tipe data konfigurasi_sumbu menjadi float
            $table->float('konfigurasi_sumbu')->nullable()->change();

            // 2. Menghapus unique constraint jika di-rollback
            $table->dropUnique(['no_uji']);
            $table->dropUnique(['no_kendaraan']);
            $table->dropUnique(['no_mesin']);
            $table->dropUnique(['no_rangka']);
        });
    }
};