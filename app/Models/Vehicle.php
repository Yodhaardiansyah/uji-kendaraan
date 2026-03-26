<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id', 'no_uji', 'no_srut', 'tgl_srut', 'no_kendaraan', 'no_mesin',
        'no_rangka', 'merk', 'tipe', 'jenis', 'tahun', 'bahan_bakar', 'cc',
        'daya_hp', 'jbb', 'jbkb', 'jbi', 'jbki', 'mst', 'berat_kosong',
        'konfigurasi_sumbu', 'ban_depan', 'ban_belakang', 'ban_ring', 'panjang',
        'lebar', 'tinggi', 'panjang_bak', 'lebar_bak', 'tinggi_bak', 'jalur_depan',
        'jalur_belakang', 'sumbu_1_2', 'sumbu_2_3', 'sumbu_3_4', 'daya_orang',
        'daya_barang', 'kelas_jalan', 'wilayah'
    ];

    // TAMBAHKAN INI: Agar format tanggal otomatis menjadi Carbon Object
    protected $casts = [
        'tgl_srut' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rfids()
    {
        return $this->hasMany(Rfid::class);
    }
}