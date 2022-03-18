<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WajibPajak extends Model
{
    use HasFactory;
    protected $table = 'data_wp';
    protected $fillable = [
        'npwp',
        'nama_wp',
        'alamat',
        'no_hp',
        'email',
        'jenis_usaha',
        'kelompok',
        'status_operasi',
    ];
}
