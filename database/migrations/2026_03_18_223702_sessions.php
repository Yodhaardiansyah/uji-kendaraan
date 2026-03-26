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
        Schema::create('sessions', function (Blueprint $table) {
            // ID Session (String karena Laravel menggunakan UUID/Random String untuk session ID)
            $table->string('id')->primary();

            // Foreign ID ke tabel users (Nullable karena tamu/guest juga punya session)
            $table->foreignId('user_id')->nullable()->index();

            // Alamat IP user
            $table->string('ip_address', 45)->nullable();

            // User Agent (Informasi browser/perangkat)
            $table->text('user_agent')->nullable();

            // Data session yang dienkripsi/disimpan (Payload)
            $table->longText('payload');

            // Timestamp aktivitas terakhir
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};