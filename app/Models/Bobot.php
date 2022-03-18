<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;
    protected $table = 'bobot_sda';
    protected $fillable = [
        'kriteria',
        'peringkat',
        'bobot',
    ];
}
