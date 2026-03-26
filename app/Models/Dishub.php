<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dishub extends Model
{
    protected $fillable = [
        'nama',
        'provinsi',
        'kota',
        'kecamatan',
        'singkatan',
        'kepala_dinas_nama',
        'kepala_dinas_nip',
        'direktur_nama',
        'direktur_nip'
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}