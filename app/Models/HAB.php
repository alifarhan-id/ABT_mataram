<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HAB extends Model
{
    use HasFactory;
    protected $table = 'hab';
    protected $fillable = ['angka'];
}
