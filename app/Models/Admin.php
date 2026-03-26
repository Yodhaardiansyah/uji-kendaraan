<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
    'nama',
    'nrp',       // Nomor Registrasi Petugas
    'pangkat',   // Pangkat/Golongan
    'dishub_id', // Relasi ke wilayah
    'email',     // Digunakan untuk login (pengganti username)
    'password',
    'role'       
];

    protected $hidden = [
        'password'
    ];

    public function dishub()
    {
        return $this->belongsTo(Dishub::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }
}