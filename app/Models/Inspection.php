<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        // I. Keterangan Hasil Uji (Tanggal)
        'tgl_uji' => 'date',
        'tgl_berlaku' => 'date',

        // B. Pemeriksaan Visual (Sesuai Migration)
        'rangka' => 'boolean', 
        'mesin' => 'boolean', 
        'tangki' => 'boolean',
        'pembuangan' => 'boolean', 
        'ban' => 'boolean', 
        'suspensi' => 'boolean',
        'rem_utama' => 'boolean', 
        'lampu' => 'boolean', 
        'dashboard' => 'boolean',
        'spion' => 'boolean', 
        'spakbor' => 'boolean', 
        'bumper' => 'boolean',
        'perlengkapan' => 'boolean', 
        'teknis' => 'boolean', 
        'darurat' => 'boolean',
        'badan' => 'boolean', 
        'converter' => 'boolean',

        // C. Pemeriksaan Manual (Sesuai Migration)
        'penerus_daya' => 'boolean',
        'kemudi' => 'boolean', 
        'rem_parkir' => 'boolean', 
        'lampu_manual' => 'boolean',
        'wiper' => 'boolean', 
        'kaca' => 'boolean', 
        'klakson' => 'boolean',
        'sabuk' => 'boolean', 
        'ukuran' => 'boolean', 
        'kursi' => 'boolean',

        // D - H. Pemeriksaan Alat Uji (Sesuai Migration)
        'emisi_solar' => 'float',
        'emisi_co' => 'float',
        'emisi_hc' => 'integer',
        'rem_utama_total' => 'float',
        'rem_utama_selisih_1' => 'float',
        'rem_utama_selisih_2' => 'float',
        'rem_utama_selisih_3' => 'float',
        'rem_utama_selisih_4' => 'float',
        'rem_parkir_tangan' => 'float',
        'rem_parkir_kaki' => 'float',
        'kincup_roda_depan' => 'float',
        'kebisingan' => 'integer',
        'lampu_kanan' => 'integer',
        'lampu_kiri' => 'integer',
        'deviasi_kanan' => 'float',
        'deviasi_kiri' => 'float',
        'speed_deviasi' => 'float',
        'alur_ban' => 'float',
    ];

    public function rfid(): BelongsTo
    {
        return $this->belongsTo(Rfid::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}