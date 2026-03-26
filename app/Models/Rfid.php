<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    protected $fillable = [
        'kode_rfid',
        'vehicle_id',
        'is_active'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }
}