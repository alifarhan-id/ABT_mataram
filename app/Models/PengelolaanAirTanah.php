<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengelolaanAirTanah extends Model
{
    use HasFactory;
    protected $table = 'pengelolaan_air_tanah';
    protected $fillable = [
        'kelompok',
        'nilai_0_500',
        'nilai_501_1500',
        'nilai_1501_3000',
        'nilai_3001_5000',
        'nilai_lebih_besar_dari_5000',
    ];
}
