<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bulan_periode extends Model
{
    /** @use HasFactory<\Database\Factories\BulanPeriodeFactory> */
    use HasFactory;
    protected $fillable = ['bulan'];
}
