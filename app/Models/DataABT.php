<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataABT extends Model
{
    use HasFactory;
    protected $table = 'data_abt';
    protected $fillable = [
        'npwp',
        'nama_wp',
        'masa',
        'tahun',
        'pemakaian',
        'jenis_usaha',
        'kelompok',
        'range_500',
        'range_1500',
        'range_3000',
        'range_5000',
        'range_lebih_dari_5000',
        'hab',
        'fs',
        'fp1',
        'fp2',
        'fp3',
        'fp4',
        'fp5',
        'npa1',
        'npa2',
        'npa3',
        'npa4',
        'npa5',
        'total_npa',
        'tarif_pajak',
        'nilai_pajak_progresive',
        'abt',
        'sanksi',
        'bunga',
        'pengurangan',
        'abt_final',
        'tanggal_penetapan',
        'bulan_penetapan',
        'tahun_penetapan',
        'user_penetapan'
    ];


}
