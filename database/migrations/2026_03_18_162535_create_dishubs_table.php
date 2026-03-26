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
    Schema::create('dishubs', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('provinsi');
        $table->string('kota');
        $table->string('kecamatan')->nullable();
        $table->string('singkatan')->nullable();

        // pejabat
        $table->string('kepala_dinas_nama');
        $table->string('kepala_dinas_nip');
        $table->string('direktur_nama');
        $table->string('direktur_nip');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishubs');
    }
};
