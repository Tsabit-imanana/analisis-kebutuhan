<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahun_periode extends Model
{
    /** @use HasFactory<\Database\Factories\TahunPeriodeFactory> */
    use HasFactory;
    protected $fillable = ['tahun'];
}
