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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nrp')->nullable();
            $table->enum('pangkat', [
                'Pembantu Penguji',
                'Penguji Pemula',
                'Penguji Tingkat Satu',
                'Penguji Tingkat Dua',
                'Penguji Tingkat Tiga',
                'Penguji Tingkat Empat',
                'Penguji Tingkat Lima',
                'Master Penguji'
            ]);

            $table->foreignId('dishub_id')->constrained()->cascadeOnDelete();

            $table->string('email')->unique();
            $table->string('password');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
